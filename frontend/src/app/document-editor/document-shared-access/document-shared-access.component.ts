import { Component, OnInit, TemplateRef } from '@angular/core';
import { NbDialogService } from '@nebular/theme';
import { ApiConnectionService } from '../../api-connection.service';
import { ActivatedRoute } from '@angular/router';
import { finalize, forkJoin } from 'rxjs';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { ItemSelectorComponent } from 'src/app/shared/item-selector/item-selector.component';

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
              private errorHandler: ErrorHandlerService) { }

  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {this.documentId = params['id']});
    if(this.documentId){
      this.viewState = 'loading';
      let requests = [
        this.connectionService.get('documents', this.documentId),
        this.connectionService.get('visibility_levels'),
        this.connectionService.get('redacta_users'),
        this.connectionService.get('access_modes'),
        this.connectionService.get('groups'),
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
            detail: group.redactaUsers
              ? group.redactaUsers.map((u: any) => `${u.name} ${u.lastName}`).join(', ')
              : ''
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
    this.dialogService.open(ItemSelectorComponent, {
      context: {
        items: [
          { id: 'user', name: 'Cuenta individual' },
          { id: 'group', name: 'Grupo' }
        ],
        itemName: 'tipo de acceso',
        filterBy: 'name',
        autocomplete: false
      }
    }).onClose.subscribe(type => {
      if (type != null) {
        if (type.id === 'user') {
          this.dialogService.open(ItemSelectorComponent, {context: {items: this.users, itemName: 'cuenta', filterBy: 'name', autocomplete: true}}).onClose.subscribe(user => {
            if (user != null) {
              this.viewState = 'loading';
              this.connectionService.post('documents_shared_accesses', {documentId: this.documentId, resourceId: user.id, resourceType: 'user'})
                .pipe(finalize(() => {this.viewState = 'rendering'}))
                .subscribe({
                  next: _ => {
                    this.getDocumentSharedAccesss();
                  },
                  error: e => {
                    this.errorHandler.handle(e);
                  }
                })
            }
          })
        } else if (type.id === 'group') {
          this.dialogService.open(ItemSelectorComponent, {
            context: {
              items: this.groups,
              itemName: 'grupo',
              filterBy: 'name',
              autocomplete: true
            }
          }).onClose.subscribe(group => {
            if (group != null) {
              this.viewState = 'loading';
              this.connectionService.post('documents_shared_accesses', {documentId: this.documentId, resourceId: group.id, resourceType: 'group'})
                .pipe(finalize(() => {this.viewState = 'rendering'}))
                .subscribe({
                  next: _ => {
                    this.getDocumentSharedAccesss();
                  },
                  error: e => {
                    this.errorHandler.handle(e);
                  }
                })
            }
          })
        }
      }
    })
  }

  removeDocumentSharedAccess(documentSharedAccessId: any) {
    this.viewState = 'loading';
    this.connectionService.delete('documents_shared_accesses', documentSharedAccessId).subscribe({
      next: _ => {
        this.getDocumentSharedAccesss();
      },
      error: e => {
        this.viewState = '';
        this.errorHandler.handle(e);
      }
    });  
  }

  getDocumentSharedAccesss(){
    this.connectionService.get('documents_shared_accesses?document_id=' + this.documentId).subscribe({
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
        this.connectionService.patch('documents_shared_accesses', sharedAccessId, {accessModeId: accessMode.id})
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