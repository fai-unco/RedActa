import { Component, OnInit, TemplateRef } from '@angular/core';
import { NbDialogService } from '@nebular/theme';
import { ApiConnectionService } from '../../api-connection.service';
import { ActivatedRoute } from '@angular/router';
import { finalize, forkJoin } from 'rxjs';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { ItemSelectorComponent } from 'src/app/shared/item-selector/item-selector.component';
import { ConfirmDialogComponent } from 'src/app/shared/confirm-dialog/confirm-dialog.component';
import { EditSharedAccessComponent } from './edit-shared-access/edit-shared-access.component';
import { AuthService } from 'src/app/auth/auth-core/auth.service';

@Component({
  selector: 'app-document-shared-access',
  templateUrl: './document-shared-access.component.html',
  styleUrls: ['./document-shared-access.component.scss']
})
export class DocumentSharedAccessComponent implements OnInit {
  documentId: any;
  viewState = '';
  exportOptions = [
    { title: 'Exportar original' }, 
    { title: 'Exportar copia fiel' }
  ];
  documentSharedAccesses: any [] = [];
  visibilityLevels: any;
  documentVisibilityLevelId!: number;
  shareLink: string = 'https://redacta.fi.uncoma.edu.ar/documentos/editar?id=';
  users: any [] = [];
  groups: any [] = [];
  accessModeName: string = 'Sin definir';
  accessModes: any [] = [];
  owner: any;

  constructor(private dialogService: NbDialogService,
              private connectionService: ApiConnectionService,
              private route: ActivatedRoute,
              private errorHandler: ErrorHandlerService,
              private authService: AuthService) { }

  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {this.documentId = params['id']});
    if(this.documentId){
      this.viewState = 'loading';
      let requests = [
        this.connectionService.get('documents', this.documentId),
        this.connectionService.get('visibility_levels'),
        this.connectionService.get('redacta_users'),
        this.connectionService.get('access_modes'),
        this.connectionService.get('groups?viewAll=true'),
      ];
      forkJoin(requests).subscribe({
        next: (res: any) => {
          this.documentSharedAccesses = res[0].data.documentSharedAccesses;
          this.documentVisibilityLevelId = res[0].data.visibilityLevelId;
          this.visibilityLevels = res[1].data;
          this.viewState = 'rendering';
          this.shareLink = this.shareLink + this.documentId;
          this.users = res[2].data.map((user: any) => {return {id: user.id, name: user.name + ' ' + user.lastName}});
          this.accessModes = res[3].data;
          this.groups = res[4].data.map((group: any) => ({
            ...group,
            //detail shows up to 4 members of the group
            detail: group.redactaUsers
              ? group.redactaUsers.slice(0, 4).map((user: any) => user.name + ' ' + user.lastName).join(', ') + (group.redactaUsers.length > 4 ? ', ...' : '')
              : 'Sin miembros'
          }));
          this.owner = this.users.find((user: any) => user.id == res[0].data.redactaUserId);
        },
        error: _ => {
          this.viewState = 'error';
        }
      });
    }
  }

  addDocumentSharedAccess() {
    // Get groups the current user belongs to
    let userGroups = this.groups.filter((group: any) => {
      return group.redactaUsers.some((user: any) => user.id == this.authService.getCurrentUserId());
    });
    this.dialogService.open(EditSharedAccessComponent, {
      context: { 
        users: this.users, 
        groups: userGroups,
        documentId: this.documentId
      },
      closeOnBackdropClick: false
    }).onClose.subscribe(success => {
      if (success) {
        this.getDocumentSharedAccesss();
      }
    })
  }

  removeDocumentSharedAccess(documentSharedAccess: any) {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma eliminar el acceso compartido a <b>${this.getSharedAccessName(documentSharedAccess)}</b>?`,
        submitType: 'danger',
        submitBtnLabel: 'Eliminar',
        apiRoute: 'document_shared_accesses',
        resourceId: documentSharedAccess.id,
        requestType: 'delete'
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((removed: boolean) => {
      if (removed) {
        this.getDocumentSharedAccesss();
      }
    });
  }

  getDocumentSharedAccesss() {
    this.viewState = 'loading';
    this.connectionService.get('document_shared_accesses?document_id=' + this.documentId).subscribe({
      next: (res: any) => {
        this.documentSharedAccesses = res.data;
        this.viewState = 'rendering';
      },
      error: _ => {
        this.viewState = 'error';
      }
    });
  }

  onVisibilityLevelSelect(id: number) {
    this.viewState = 'loading';
    this.connectionService.patch('documents', this.documentId, { visibilityLevelId: id }, 'visibility_level')
      .pipe(finalize(() => this.viewState = 'rendering'))
      .subscribe({
        next: _ => {
          this.documentVisibilityLevelId = id;
        },
        error: e => {
          this.errorHandler.handle(e);
        }
      })
  }

  openShareLinkDialog(dialog: TemplateRef<any>) {
    this.dialogService.open(dialog, { context: this.shareLink});
  }

  copyShareLink() {
    navigator.clipboard.writeText(this.shareLink);
  }

  changeAccessMode(sharedAccessId: number) {
    this.dialogService.open(ItemSelectorComponent, {context: {items: this.accessModes, itemName: 'modo de acceso', filterBy: 'label', autocomplete: false}}).onClose.subscribe(accessMode => {
      if (accessMode != null) {
        this.viewState = 'loading';
        this.connectionService.patch('document_shared_accesses', sharedAccessId, {accessModeId: accessMode.id})
        .pipe(finalize(() => {this.viewState = 'rendering'}))
          .subscribe({
            next: _ => {
              let index = this.documentSharedAccesses.findIndex((access: any) => access.id == sharedAccessId);
              this.documentSharedAccesses[index].accessModeId = accessMode.id;
            },
            error: e => {
              this.errorHandler.handle(e);
            }
          })
      }
    })
  }

  getAccessMode(accessModeId: number) {
    let index = this.accessModes.findIndex((accessMode: any) => accessMode.id == accessModeId);
    return this.accessModes[index];
  }

  getSharedAccessName(access: any): string {
    let listToSearch = access.resourceType == 'redactaUser' ? this.users : this.groups;
    let index = listToSearch.findIndex((item: any) => item.id == access.resourceId);
    return index != -1 ? listToSearch[index].name : 'Sin definir';
  }

  getGroupMembers(groupId: number): any[] {
    const group = this.groups.find((g: any) => g.id === groupId);
    return group && group.redactaUsers ? group.redactaUsers : [];
  }
}