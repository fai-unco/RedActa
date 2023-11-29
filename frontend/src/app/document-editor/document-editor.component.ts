import { Component, ElementRef, OnInit, TemplateRef, ViewChild} from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { NbDialogService, NbMenuService} from '@nebular/theme';
import { DatePipe, Location } from '@angular/common';
import { ApiConnectionService } from '../api-connection.service';
import { Subscription, finalize, forkJoin } from 'rxjs';
import { ErrorDialogComponent } from '../shared/error-dialog/error-dialog.component';
import { InitSettingsDialogComponent } from './init-settings-dialog/init-settings-dialog.component';

@Component({
  selector: 'app-document-editor',
  templateUrl: './document-editor.component.html',
  styleUrls: ['./document-editor.component.scss',],
  providers: [DatePipe]
})

export class DocumentEditorComponent implements OnInit {
  issuer: any;
  documentType: any;
  form!: FormGroup; 
  nameOnFocus: boolean = false;
  submitting: boolean = false;
  error: boolean = false;
  documentId: any;
  actionResult!: string;
  state = '';
  anexosData: any[] = [];
  @ViewChild('errorDialog') errorDialog!: any; 
  @ViewChild('documentNameInput') documentNameInput!: ElementRef;
  anexosToBeRemoved: any = [];   
  menuSubscription!: Subscription;
  exportOptions = [
    { title: 'Exportar original' }, 
    { title: 'Exportar copia fiel' }
  ];
  hints: {[index: string]: any} = {
    Visto: 'No incluir "Visto" al inicio, se añade en forma automática al exportar el documento',
    Considerando: '',
    Resuelve: 'Solo ingresar el contenido del artículo, no colocar punto al finalizar'
  }
  
  constructor(private fb: FormBuilder, 
              private dialogService: NbDialogService, 
              private route: ActivatedRoute,
              private router: Router,
              private datePipe: DatePipe, 
              private connectionService: ApiConnectionService,
              private nbMenuService: NbMenuService, 
              private location: Location
              ) {}

  ngOnInit(): void {
    this.menuSubscription = this.nbMenuService.onItemClick().subscribe((event) => {
      if(event.item.title === 'Exportar original'){
        this.export()
      } else if(event.item.title === 'Exportar copia fiel'){
        this.export(true);
      }
    });
    this.route.queryParams.subscribe(params => this.documentId = params['id']);
    if (!this.documentId) {
      this.dialogService.open(InitSettingsDialogComponent).onClose.subscribe(data => {
        if(data){
          this.state = 'loading'
          this.initialize(data);
        } else {
          this.router.navigateByUrl('/');
        }
      });
    } else {
      this.state = 'loading';
      this.connectionService.get('documents', this.documentId, {headers: {accept: 'application/json'}}).subscribe({
        next: (res: any) => this.initialize(res.data), 
        error: e => {
          this.errorHandler(e, '/');
          this.state = '';
        }
      });
    }
  }

  ngOnDestroy(): void {
    if(this.menuSubscription){
      this.menuSubscription.unsubscribe();
    }
  }

  private initialize(data?: any){
    let requests = [
      this.connectionService.get('document_types', data.documentTypeId), 
      this.connectionService.get('issuers', data.issuerId),
    ];
    forkJoin(requests).subscribe({
      next: (res: any) => {
        let formArrayControlInitContent = [];
        this.documentType = res[0].data;
        this.issuer = res[1].data;
        this.form = this.fb.group({
          name: ['Nuevo documento'],
          documentTypeId: ['', Validators.required],
          number: ['', Validators.required],
          issuerId: ['', Validators.required],
          issueDate: ['', Validators.required],
          subject: ['', Validators.required],    
          destinatary: ['', Validators.required],    
          adReferendum: [false, Validators.required],
          hasAnexoUnico: [false, Validators.required],
          headingId: ['1', Validators.required],    
          operativeSectionBeginningId: ['1', Validators.required],    
          body: this.fb.group({})
        });
        if(!this.documentId){
          formArrayControlInitContent.push('');
        }
        if([1, 2, 3].includes(data.documentTypeId)){ //si el documento es una resolución, disposición o declaración
          this.body.addControl('visto', this.fb.control(''));
          this.body.addControl('considerando', this.fb.array(formArrayControlInitContent));
          this.body.addControl('articulos', this.fb.array(formArrayControlInitContent));
        } else if([4, 5, 6].includes(data.documentTypeId)){ //si el documento es un acta, memo o nota 
          this.body.addControl('cuerpo', this.fb.control(''));
        } 
        for(let [key, value] of Object.entries(data)) {
          switch(key){
            case 'body':
              for (let [key, value] of Object.entries(data.body)) {
                if(Array.isArray(value)){
                  value?.forEach((elem) => (this.body.get(key) as FormArray).push(this.fb.control(elem)));
                } else {
                  this.body.get(key)?.setValue(value);
                }
              }
              break;
            case 'issueDate':
              this.form.get('issueDate')?.setValue(new Date (value + 'T00:00:00-03:00'));
              break;
            case 'anexos':
              for(let anexo of data.anexos){
                this.addAnexo(anexo.id, anexo.index, anexo.title, anexo.subtitle, anexo.content, anexo.file);
              }
              break;
            default:
              this.form.get(key)?.setValue(value);
          }
        }
        this.state = 'showForm';
      },
      error: e => {
        this.state = '';
        this.errorHandler(e, '/');
      }
    });
  }
  
  get body(){
    return this.form.get('body') as FormGroup;
  }

  get hasAnexoUnico(){
    return this.form.get('hasAnexoUnico')?.value;
  }

  nameOnInput(value: string){
    this.form.get('name')?.setValue(value);
  }

  nameOnBlur(){
    if(!this.form.get('name')?.value){
      this.form.get('name')?.setValue('Nuevo documento');
    }
    this.nameOnFocus = false;
  }

  adReferendumOnChange(){
    let adReferendum = this.form.get('adReferendum')?.value;
    this.form.get('adReferendum')?.setValue(!adReferendum);  
  }

  hasAnexoUnicoOnChange(){
    this.form.get('hasAnexoUnico')?.setValue(!this.hasAnexoUnico);
  }

  getBodyFormArray(formControlName: string) {
    return this.body.get(formControlName) as FormArray;
  }

  addItemToBodyFormArray(item: any, formArrayName: string) {
    (this.body.get(formArrayName) as FormArray).push(this.fb.control(item));
  }
  
  addAnexo(id='', index=this.anexosData.length, title='', subtitle='', content='', file: any = null) {
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
    this.anexosData.push({form: newAnexo, fileData: file});
  }
   
  removeItemFromBodyFormArray(dialog: TemplateRef<any>, index: number, formArrayName: string, item: string) {
    this.dialogService.open(dialog, {context: {index: index, item: item}})
      .onClose.subscribe(remove => {
        if(remove){
          (this.body.get(formArrayName) as FormArray).removeAt(index);
        }
      });
  }

  private errorHandler(error?: any, urlRedirect?: any) {
    this.error = true;
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

  cloneDocument(){
    this.documentId = null;
    this.form.get('name')?.setValue('Nuevo documento');
    this.location.replaceState('/documentos/editar');
  }

  submit() {
    this.error = false;
    let data = this.form.value;
    let request;
    this.actionResult = '';
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
          this.location.replaceState('/documentos/editar?id=' + this.documentId);
        }
        if(this.anexosData.length > 0 || this.anexosToBeRemoved.length > 0){
          this.saveAnexos();
        } else {
          this.actionResult = 'Guardado!';
        }
      },
      error: e => {
        this.errorHandler(e);
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
      if(item.form.get('id')?.value == ''){
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
      },
      error: e => {
        this.errorHandler(e);
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
    this.actionResult = '';
    this.error = false;
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
        },
        error: e => {
          this.errorHandler({error:{message: 'Error en el servidor. Reintente la operación'}});
        }
      })
  }
}