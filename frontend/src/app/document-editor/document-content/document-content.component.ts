import { Component, ElementRef, OnInit, TemplateRef, ViewChild} from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { NbDialogService, NbMenuService} from '@nebular/theme';
import { DatePipe } from '@angular/common';
import { ApiConnectionService } from '../../api-connection.service';
import { Observable, Subscription, filter, finalize, forkJoin, map, of } from 'rxjs';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { ItemSelectorComponent } from 'src/app/shared/item-selector/item-selector.component';

@Component({
  selector: 'app-document-content',
  templateUrl: './document-content.component.html',
  styleUrls: ['./document-content.component.scss',],
  providers: [DatePipe]
})

export class DocumentContentComponent implements OnInit {
  documentType: any;
  form!: FormGroup;
  operativeSectionBeginnings: any = [];
  headings: any = [];
  nameOnFocus: boolean = false;
  submitting: boolean = false;
  documentId: any;
  actionResult!: string;
  state = '';
  anexosData: any[] = [];
  anexosToBeRemoved: any[] = [];
  issuers: any[] = [];
  issuerName: string = 'Sin definir';
  exportOptionsMenuSubscription!: Subscription;
  articleMenuSubscriptions: Subscription[] = [];
  anexosMenuSubscriptions: Subscription[] = [];
  @ViewChild('removeItemDialog', { read: TemplateRef }) removeItemDialog!:TemplateRef<any>;
  issuerSettings: any;
  @ViewChild('scrollable') scrollable!: ElementRef;
  filteredIssuers!: Observable<any[]>;
  exportOptions = [
    { title: 'Exportar original' },
    { title: 'Exportar copia fiel' }
  ];
  articleActions = [
    { title: 'Agregar 1 arriba' },
    { title: 'Agregar 1 abajo' },
    { title: 'Insertar últ. artículo predef.'},
    { title: 'Eliminar' }
  ];
  hints: {[index: string]: any} = {
    Visto: 'No incluir "Visto" al inicio, se añade en forma automática al exportar el documento'
  }

  constructor(private fb: FormBuilder, 
              private dialogService: NbDialogService, 
              private route: ActivatedRoute,
              private router: Router,
              private datePipe: DatePipe, 
              private connectionService: ApiConnectionService,
              private nbMenuService: NbMenuService, 
              private errorHandler: ErrorHandlerService
            ) {}

  ngOnInit(): void {
    this.exportOptionsMenuSubscription = this.nbMenuService.onItemClick()
      .pipe(
        filter(({ tag }) => tag === 'document-content-export-menu'),
      )
      .subscribe((event) => {
        if (event.item.title === 'Exportar original') {
          this.export()
        } else if (event.item.title === 'Exportar copia fiel') {
          this.export(true);
        }
      });
    this.route.queryParams.subscribe(params => {
      this.documentId = params['id'];
      this.connectionService.get('document_types').subscribe({
        next: (documentTypesRes: any) => {
          if (!this.documentId && !this.documentType) {
            this.dialogService.open(ItemSelectorComponent, {context: {items: documentTypesRes.data, itemName: 'tipo de documento', filterBy: 'description'}})
            .onClose.subscribe(documentType => {
              if (documentType != null) {
                this.state = 'loading',
                this.documentType = documentType;
                this.initialize();
              } else {
                this.router.navigateByUrl('/');
              }
            })
          } else if (this.documentId) {
            this.state = 'loading';
            this.connectionService.get('documents', this.documentId, {headers: {accept: 'application/json'}}).subscribe({
              next: (documentRes: any) => {
                let documentTypeIndex = documentTypesRes.data.findIndex((documentType: any) => documentType.id == documentRes.data.documentTypeId);
                this.documentType = documentTypesRes.data[documentTypeIndex];
                this.initialize(documentRes.data)
              },
              error: e => {
                this.errorHandler.handle(e, '/');
                this.state = '';
              }
            }); 
          }
        },
        error: e => {
          this.errorHandler.handle(e, '/');
        }
      });
    });
  }

  ngOnDestroy(): void {
    if(this.exportOptionsMenuSubscription){
      this.exportOptionsMenuSubscription.unsubscribe();
    }
    this.articleMenuSubscriptions.forEach(sub => sub.unsubscribe());
    this.anexosMenuSubscriptions.forEach(sub => sub.unsubscribe())
  }

  private initialize(data: any = {}) {
    let requests = [
      this.connectionService.get('issuers')
    ];
    forkJoin(requests).subscribe({
      next: (res: any) => {
        this.issuers = res[0].data;
        this.form = this.fb.group({
          name: ['Nuevo documento'],
          documentTypeId: [this.documentType.id, Validators.required],
          number: [null, Validators.required],
          issuerId: [null, Validators.required],
          issueDate: [null, Validators.required],
          subject: [null, Validators.required],    
          destinatary: [null, Validators.required],    
          hasAnexoUnico: [false, Validators.required],
          headingId: [null, Validators.required],    
          operativeSectionBeginningId: [null, Validators.required],    
          body: this.fb.group({})
        });
        if([1, 2, 3].includes(this.documentType.id)){ 
          //si el documento es una resolución, disposición o declaración
          this.body.addControl('visto', this.fb.control(''));
          this.body.addControl('considerando', this.fb.array(this.documentId ? [] : ['']));
          this.body.addControl('articulos', this.fb.array([]));
          if (!this.documentId) {
            this.addArticulo('', 0);
          }
        } else if([4, 5, 6].includes(this.documentType.id)){ 
          //si el documento es un acta, memo o nota 
          this.body.addControl('cuerpo', this.fb.control(''));
          if ([5, 6].includes(this.documentType.id)) {
            this.body.addControl('startingPhrase', this.fb.control(null));
            this.body.addControl('partingPhrase', this.fb.control(null));
          }
        } 
        for(let [key, value] of Object.entries(data)) {
          switch(key){
            case 'body':
              for (let [key, value] of Object.entries(data.body)) {
                if (Array.isArray(value)) {
                  if (key == 'articulos') {
                    value?.forEach((elem: any) => (this.addArticulo(elem)));
                  } else {
                    value?.forEach((elem: any) => (this.body.get(key) as FormArray).push(this.fb.control(elem)));
                  }  
                } else {
                  this.body.get(key)?.setValue(value);
                }
              }
              break;
            case 'issueDate':
              if (value) {
                this.form.get('issueDate')?.setValue(new Date (value + 'T00:00:00-03:00'));
              }
              break;
            case 'anexos':
              data.anexos.forEach((anexo: any) => this.addAnexo(anexo.id, anexo.index, anexo.title, anexo.subtitle, anexo.content, anexo.file));
              break;
            default:
              this.form.get(key)?.setValue(value);
          }
        }
        if (data.issuerId) {
          let index = this.issuers.findIndex(issuer => issuer.id == data.issuerId);
          this.setIssuer(this.issuers[index], data);
        } else {
          this.state = 'showForm';
        }
      },
      error: e => {
        this.state = '';
        this.errorHandler.handle(e, '/');
      }
    });
  }
  
  get body(){
    return this.form.get('body') as FormGroup;
  }

  get hasAnexoUnico(){
    return this.form.get('hasAnexoUnico')?.value;
  }

  get operativeSectionBeginningIdFc(){
    return this.form.get('operativeSectionBeginningId') as FormControl;
  }

  get headingIdFc(){
    return this.form.get('headingId') as FormControl;
  }

  changeIssuer() {
    this.dialogService.open(ItemSelectorComponent, {context: {items: this.issuers, itemName: 'emisor', filterBy: 'description', autocomplete: true, allowUndefined: true}})
      .onClose.subscribe(issuer => {
        if (issuer != null) {
          this.setIssuer(issuer);
        }
      })
  }

  setIssuer(issuer: any, document: any = null) {
    if (issuer.id) {
      this.state = 'loading';
      let requests = [
        this.connectionService.get('headings?issuer_id=' + issuer.id),
        this.connectionService.get('operative_section_beginnings?issuer_id=' + issuer.id),
        this.connectionService.get('issuers_settings?issuer_id=' + issuer.id)
      ];    
      forkJoin(requests)
        .pipe(finalize(() => this.state = 'showForm'))
        .subscribe({
          next: (res: any) => {
            this.headings = res[0].data;
            this.operativeSectionBeginnings = res[1].data;
            this.issuerName = issuer.description;
            this.issuerSettings = res[2].data;
            setTimeout(() => {
              this.form.get('issuerId')?.setValue(document && document.issuerId ? document.issuerId : issuer.id);
              this.form.get('headingId')?.setValue(document && document.headingId ? document.headingId : res[2].data.suggestedHeadingId);
              this.form.get('operativeSectionBeginningId')?.setValue(document && document.operativeSectionBeginningId ? document.operativeSectionBeginningId : res[2].data.suggestedOperativeSectionBeginningId);
              if ([5, 6].includes(this.documentType.id)) {
                this.body.get('startingPhrase')?.setValue(document && document.body ? document.body.startingPhrase : res[2].data.suggestedStartingPhrase);
                this.body.get('partingPhrase')?.setValue(document && document.body ? document.body.partingPhrase : res[2].data.suggestedPartingPhrase);
              }
            });
          },
          error: e => {
            this.errorHandler.handle(e);
          }
      })
    } else {
      this.headings = [];
      this.operativeSectionBeginnings = [];
      this.issuerName = 'Sin definir';
      this.issuerSettings = [];
      this.form.get('issuerId')?.setValue('');
      this.form.get('headingId')?.setValue('');
      this.form.get('operativeSectionBeginningId')?.setValue('');
      if (this.documentType.id == 6) {
        this.body.get('startingPhrase')?.setValue('');
        this.body.get('partingPhrase')?.setValue('');
      }
    }
  }

  hasAnexoUnicoOnChange(){
    this.form.get('hasAnexoUnico')?.setValue(!this.hasAnexoUnico);
  }

  getBodyFormArray(formControlName: string) {
    return this.body.controls[formControlName] as FormArray;
  }

  addArticulo(item: any,  position?: number) {
    let articles = this.body.get('articulos') as FormArray;
    let articlesLength = articles.length
    if (position == null) {
      position = articlesLength;
    }  
    let subscription = this.nbMenuService.onItemClick().pipe(
        filter(({ tag }) => tag =='article-menu-' + articlesLength),
      ).subscribe((event: any) => {
        let action = event.item.title;
        if (action == 'Eliminar') {
          this.removeArticulo(articlesLength);
        } else if (action == 'Agregar 1 arriba') {
          this.addArticulo ('', articlesLength);
        } else if (action == 'Agregar 1 abajo') {
          this.addArticulo ('', articlesLength + 1);
        } else {
          this.body.get('articulos')?.get(articlesLength.toString())?.setValue(this.issuerSettings.suggestedOperativeSectionLastArticle);
        }
      });
    this.articleMenuSubscriptions.push(subscription);
    articles.insert(position, this.fb.control(item));
  }
  
  addAnexo(id = '', index = this.anexosData.length, title = '', subtitle = '', content = '', file: any = null) {
    let fileId = '';
    if(file){
      fileId = file.id; 
    }
    let newAnexo = this.fb.group({
      id: this.fb.control(id),
      index: this.fb.control(index),
      title: this.fb.control(title),
      subtitle: this.fb.control(subtitle),
      fileId: this.fb.control(fileId),
      content: this.fb.control(content),
      documentId: this.fb.control(this.documentId) 
    });
    this.anexosData.splice(index, 0, {form: newAnexo, fileData: file});
    setTimeout(() => {
      if (index + 1 == this.anexosData.length) {
        this.scrollable.nativeElement.scrollTop = this.scrollable.nativeElement.scrollHeight;
      }
    }, 50);
  }
   
  removeArticulo(index: number) {
    this.dialogService.open(this.removeItemDialog, {context: {index: index, item: 'articulo'}})
      .onClose.subscribe(remove => {
        if(remove){
          (this.body.get('articulos') as FormArray).removeAt(index);
          this.articleMenuSubscriptions.pop();
        }
      });
  }

  cloneDocument(){
    this.documentId = null;
    this.form.get('name')?.setValue('Nuevo documento');
    this.form.get('hasAnexoUnico')?.setValue(false);
    this.form.get('number')?.setValue(null);
    this.router.navigate([], 
      {
        relativeTo: this.route,
        queryParams: null,
      }
    );
    this.anexosData = [];
    this.actionResult = 'Copiado!';
    setTimeout(() => {
      this.actionResult = '';
    }, 6000);
  }

  submit() {
    this.actionResult = '';
    let data = this.form.value;
    let request;
    data.issueDate = this.datePipe.transform(this.form.get('issueDate')?.value, 'yyyy-MM-dd');
    this.submitting = true;
    if(!this.documentId){
      request = this.connectionService.post('documents', data);
    } else {
      request = this.connectionService.patch('documents', this.documentId, data)
    }
    request.pipe(
      finalize(() => this.submitting = false)
    )
    .subscribe({
      next: (res: any) => {
        if(res.status == '201'){
          this.documentId = res.data.id;
          this.router.navigate([], 
            {
              relativeTo: this.route,
              queryParams: { id: this.documentId },
              queryParamsHandling: 'merge'
            }
          );
        }
        if(this.anexosData.length > 0 || this.anexosToBeRemoved.length > 0){
          this.saveAnexos();
        } else {
          this.actionResult = 'Guardado!';
          setTimeout(() => {
            this.actionResult = '';
          }, 6000);
        }
      },
      error: e => {
        this.errorHandler.handle(e);
      }
    })
  } 
  
  private saveAnexos(){
    let requests = [];
    for (let id of this.anexosToBeRemoved){
      requests.push(this.connectionService.delete('anexos', id));
    }
    for (let item of this.anexosData) {
      item.form.get('documentId')?.setValue(this.documentId);
      if (item.form.get('id')?.value == '') {
        requests.push(this.connectionService.post('anexos', item.form.value));
      } else {
        requests.push(this.connectionService.patch('anexos', item.form.get('id')?.value, item.form.value));
      }
    }
    forkJoin(requests).subscribe({
      next: (responses: any) => {
        for (const [index, item] of this.anexosData.entries()) {
          if(responses[index].status == '201'){
            item.form.get('id')?.setValue(responses[index].data.id);
          }
        }; 
        this.anexosToBeRemoved = [];
        this.actionResult = 'Guardado!';
          setTimeout(() => {
            this.actionResult = '';
          }, 6000);
      },
      error: e => {
        this.errorHandler.handle(e);
      }
    }); 
  }

  removeAnexo(dialog: TemplateRef<any>, index: number,  item: string) {
    this.dialogService.open(dialog, {context: {index: index, item: item}}).onClose.subscribe(remove => {
      if(remove){
        let anexoId = this.anexosData[index].form.get('id').value;
        this.anexosData.splice(index, 1);
        if(anexoId != ''){
          this.anexosToBeRemoved.push(anexoId);
        }
      }
    });
  }

  export(isCopy = false){
    this.submitting = true;
    this.connectionService.get('documents', this.documentId, {headers: {accept:'application/pdf'}, responseType: 'blob', observe: 'response', params: {is_copy: isCopy}})
      .pipe(
        finalize(() => this.submitting = false),
      )
      .subscribe({
        next: (res: any) => {
          let file = new Blob([res.body], {type: 'application/pdf'});
          let fileURL = URL.createObjectURL(file);
          const link = document.createElement('a');
          let filename='file.pdf';
          const source = fileURL;
          link!.href = source;
          let contentDispositionHeader = res.headers.get('Content-Disposition');
          if (contentDispositionHeader) {
            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            var matches = filenameRegex.exec(contentDispositionHeader);
            if (matches != null && matches[1]) { 
              filename = matches[1].replace(/['"]/g, '');
            }
          }
          link!.download = filename;
          link.click();
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
}