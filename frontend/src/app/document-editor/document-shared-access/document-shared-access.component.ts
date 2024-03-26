import { Component, OnInit } from '@angular/core';
import { NbDialogService } from '@nebular/theme';
import { ApiConnectionService } from '../../api-connection.service';
import { ActivatedRoute } from '@angular/router';
import { DocumentService } from '../../shared/document.service';
import { finalize } from 'rxjs';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { UserSelectorComponent } from './user-selector/user-selector.component';

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


  constructor(private dialogService: NbDialogService,
              private connectionService: ApiConnectionService,
              private route: ActivatedRoute,
              private documentService: DocumentService,
              private errorHandler: ErrorHandlerService) { }


  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {this.documentId = params['id']});
    if(this.documentId){
      this.viewState = 'loading';
      this.getDocumentSharedAccesss();
    }
  }

  addDocumentSharedAccess() {
    this.dialogService.open(UserSelectorComponent, {context: {documentId: this.documentId}}).onClose.subscribe(status => {
      if(status){
        this.getDocumentSharedAccesss();
      }
    });
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
      error: e => {
        this.viewState = 'error';
      }
    });
  }

  

  export(trueCopy = false){
    this.viewState = 'loading';
    this.documentService.exportDocument(this.documentId, trueCopy)
      .pipe(finalize(()=> this.viewState = 'rendering'))
      .subscribe({
        error: _ => {
          this.errorHandler.handle();
        }
      })
  }

}
