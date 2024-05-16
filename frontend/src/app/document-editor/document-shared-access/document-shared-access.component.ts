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

  //@Input('documentId') 
  documentId: any;
  viewState = '';
  documentDocumentSharedAccesss: any = [];
  exportOptions = [
    { title: 'Exportar original' }, 
    { title: 'Exportar copia fiel' }
  ];
  documentSharedAccesses: any;
  visibilityLevels: any;
  documentVisibilityLevelId!: number;
  shareLink: string = 'https://redacta.fi.uncoma.edu.ar/documentos/editar?id=';
  users: any [] = [];

  constructor(private dialogService: NbDialogService,
              private connectionService: ApiConnectionService,
              private route: ActivatedRoute,
              private errorHandler: ErrorHandlerService) { }

  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {this.documentId = params['id']});
    if(this.documentId){
      this.viewState = 'loading';
      let requests = [
        this.connectionService.get('documents_shared_accesses?document_id=' + this.documentId),
        this.connectionService.get('documents', this.documentId),
        this.connectionService.get('visibility_levels'),
        this.connectionService.get('redacta_users')
      ];
      forkJoin(requests).subscribe({
        next: (res: any) => {
          this.documentSharedAccesses = res[0].data;
          this.documentVisibilityLevelId = res[1].data.visibilityLevelId;
          this.visibilityLevels = res[2].data;
          this.viewState = 'rendering';
          this.shareLink = this.shareLink + this.documentId;
          this.users = res[3].data.map((user: any) => {return {id: user.id, name: user.name + ' ' + user.lastName}});
        },
        error: _ => {
          this.viewState = 'error';
        }
      });
    }
  }

  addDocumentSharedAccess() {
    this.dialogService.open(ItemSelectorComponent, {context: {items: this.users, itemName: 'usuario', filterBy: 'name', autocomplete: true}}).onClose.subscribe(user => {
      if (user != null) {
        this.connectionService.post('documents_shared_accesses', {documentId: this.documentId, redactaUserId: user.id})
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
    this.connectionService.patch('documents', this.documentId, { visibilityLevelId: id })
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
}
