import { Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { NbDialogService, NbMenuService } from '@nebular/theme';
import { ApiConnectionService } from '../../api-connection.service';
import { StampSelectorComponent } from './signature-selector/signature-selector.component';
import { ActivatedRoute } from '@angular/router';
import { DocumentService } from '../../shared/document.service';
import { Subscription, filter, finalize } from 'rxjs';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';

@Component({
  selector: 'app-document-signatures',
  templateUrl: './document-signatures.component.html',
  styleUrls: ['./document-signatures.component.scss']
})
export class DocumentStampsComponent implements OnInit {

  documentId: any;
  documentTypeId: any;
  viewState = 'rendering';
  form!: FormGroup;
  actionResult: string = '';
  exportOptions = [
    { title: 'Exportar original' },
    { title: 'Exportar copia fiel' }
  ];
  stampOptions = [
    { title: 'Importar sello' },
    { title: 'Agregar 1 arriba' },
    { title: 'Agregar 1 abajo' },
    { title: 'Eliminar' }
  ];
  menuSubscription!: Subscription;
  stampMenuSubscriptions: Subscription[] = [];
  @ViewChild('removeStampDialog', { read: TemplateRef }) removeStampDialog!:TemplateRef<any>;

  constructor(private dialogService: NbDialogService,
              private connectionService: ApiConnectionService,
              private route: ActivatedRoute,
              private documentService: DocumentService,
              private nbMenuService: NbMenuService,
              private errorHandler: ErrorHandlerService,
              private fb: FormBuilder) { }


  ngOnInit(): void {
    this.form = this.fb.group({
      stamps: this.fb.array([])
    });
    this.route.queryParams.subscribe(params => {this.documentId = params['id']});
    this.menuSubscription = this.nbMenuService.onItemClick()
    .pipe(
      filter(({ tag }) => tag === 'document-stamps-export-menu'),
    )
    .subscribe((event) => {
      if(event.item.title === 'Exportar original'){
        this.export()
      } else if(event.item.title === 'Exportar copia fiel'){
        this.export(true);
      }
    });
    if (this.documentId) {
      this.viewState = 'loading';
      this.connectionService.get('documents', this.documentId).subscribe({
        next: (res: any) => {
          res.data.stamps.forEach((elem: any) => (this.addStamp(elem)));
          this.viewState = 'rendering'
        },
        error: _ => {
          this.viewState = 'error';
        }
      })
    } else {
      this.addStamp('');
    }
  }

  get documentStamps() {
    return this.form.get('stamps') as FormArray
  }

  addStamp(item: any,  position?: number) {
    let documentStampsNumber = this.documentStamps.length
    if (position == null) {
      position = documentStampsNumber;
    }  
    let subscription = this.nbMenuService.onItemClick().pipe(
        filter (({ tag }) => tag =='stamp-menu-' + documentStampsNumber),
      ).subscribe ((event: any) => {
        let action = event.item.title;
        if (action == 'Eliminar'){
          this.removeStamp(documentStampsNumber);
        } else if (action == 'Agregar 1 arriba') {
          this.addStamp ('', documentStampsNumber);
        } else if (action == 'Agregar 1 abajo') {
          this.addStamp ('', documentStampsNumber + 1);
        } else {
          this.dialogService.open(StampSelectorComponent).onClose.subscribe(output => {
            if (output) {
              this.documentStamps.get(documentStampsNumber + '')?.setValue(output);
            }
          });
        }
      });
    this.stampMenuSubscriptions.push(subscription);
    this.documentStamps.insert(position, this.fb.control(item));
  }

  removeStamp(index: number) {
    this.dialogService.open(this.removeStampDialog, {context: {index: index, item: 'articulo'}})
    .onClose.subscribe(remove => {
      if(remove){
        this.documentStamps.removeAt(index);
        this.stampMenuSubscriptions.pop();
      }
    });
  }

  export(trueCopy = false) {
    this.viewState = 'loading';
    this.documentService.exportDocument(this.documentId, trueCopy)
      .pipe(finalize(()=> this.viewState = 'rendering'))
      .subscribe({
        next: _ => {
          this.actionResult = 'PDF generado!';
          setTimeout(() => {
            this.actionResult = '';
          }, 6000);
        },
        error: _ => {
          this.errorHandler.handle();
        }
      })
  }

  submit() {
    this.connectionService.patch('documents', this.documentId, this.form.value)
      .subscribe({
        next: _ => {
          this.actionResult = 'Guardado!';
          setTimeout(() => {
            this.actionResult = '';
          }, 6000);
        }
      })
  }
}