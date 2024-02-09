import { InputModalityDetector } from '@angular/cdk/a11y';
import { Component, Input, OnInit } from '@angular/core';
import { NbDialogService, NbMenuService } from '@nebular/theme';
import { ApiConnectionService } from '../../api-connection.service';
import { SignatureSelectorComponent } from './signature-selector/signature-selector.component';
import { ErrorDialogComponent } from '../../shared/error-dialog/error-dialog.component';
import { ActivatedRoute, Router } from '@angular/router';
import { DocumentService } from '../../shared/document.service';
import { Subscription, filter, finalize } from 'rxjs';

@Component({
  selector: 'app-document-signatures',
  templateUrl: './document-signatures.component.html',
  styleUrls: ['./document-signatures.component.scss']
})
export class DocumentSignaturesComponent implements OnInit {

  //@Input('documentId') 
  documentId: any;
  viewState = '';
  documentSignatures: any = [];
  exportOptions = [
    { title: 'Exportar original' }, 
    { title: 'Exportar copia fiel' }
  ];
  document: any;
  menuSubscription!: Subscription;


  constructor(private dialogService: NbDialogService,
              private connectionService: ApiConnectionService,
              private router: Router,
              private route: ActivatedRoute,
              private documentService: DocumentService,
              private nbMenuService: NbMenuService) { }


  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {this.documentId = params['id']});
    this.menuSubscription = this.nbMenuService.onItemClick()
    .pipe(
      filter(({ tag }) => tag === 'document-signatures-export-menu'),
    )
    .subscribe((event) => {
      if(event.item.title === 'Exportar original'){
        this.export()
      } else if(event.item.title === 'Exportar copia fiel'){
        this.export(true);
      }
    });
    
    if(this.documentId){
      this.viewState = 'loading';
      this.getDocumentSignatures();
    }
  }

  addSignature() {
    this.dialogService.open(SignatureSelectorComponent, {context: {documentId: this.documentId}}).onClose.subscribe(status => {
      if(status){
        this.getDocumentSignatures();
      }
    });
  }

  editSignature(signatureId: any) {
    this.dialogService.open(SignatureSelectorComponent, {context: {signatureId: signatureId}}).onClose.subscribe(status => {
      if(status){
        this.getDocumentSignatures();
      }
    });
  }

  removeSignature(signatureId: any) {
    this.viewState = 'loading';
    this.connectionService.delete('signatures', signatureId).subscribe({
      next: _ => {
        this.getDocumentSignatures();
      },
      error: e => {
        this.viewState = '';
        this.errorHandler(e, '/');
      }
    });  
  }

  getDocumentSignatures(){
    this.connectionService.get('documents', this.documentId, {headers: {accept: 'application/json'}}).subscribe({
      next: (res: any) => {
        this.document = res.data;
        this.viewState = 'rendering';
      },
      error: e => {
        this.viewState = 'error';

      }
    });
  }

  private errorHandler(error?: any, urlRedirect?: any) {
    this.openErrorDialog(error.error.message, urlRedirect);
  }

  openErrorDialog (errorMsg: string, urlRedirect?: any){
    this.dialogService.open(ErrorDialogComponent, {
      context: {
        msg: errorMsg,
      },
    })
    .onClose.subscribe(_ => {
      if(urlRedirect){
        this.router.navigateByUrl(urlRedirect);
      }
    });
  }

  export(trueCopy = false){
    this.viewState = 'loading';
    this.documentService.exportDocument(this.documentId, trueCopy)
      .pipe(finalize(()=> this.viewState = 'rendering'))
      .subscribe({
        error: _ => {
          this.errorHandler({error:{message: 'Error en el servidor. Reintente la operación'}});
        }
      })
  }

}
