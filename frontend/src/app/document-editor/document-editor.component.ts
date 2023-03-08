import { Component, ElementRef, OnInit, TemplateRef, ViewChild} from '@angular/core';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import {  NbDialogService} from '@nebular/theme';
import { DatePipe } from '@angular/common';
import { ApiConnectionService } from '../api-connection.service';
import { finalize, forkJoin } from 'rxjs';


@Component({
  selector: 'app-document-editor',
  templateUrl: './document-editor.component.html',
  styleUrls: ['./document-editor.component.scss',],
  providers: [DatePipe]
})


export class DocumentEditorComponent implements OnInit {

  issuers: any;
  documentTypes: any;
  form!: FormGroup; 
  nameOnFocus: boolean = false;
  submitting: boolean = false;
  error: boolean = false;
  documentId: string = '';
  actionResult!: string;
  formIsLoaded!: boolean;
  @ViewChild('errorDialog') errorDialog!: any; 
  @ViewChild('documentNameInput') documentNameInput!: ElementRef; 

  constructor(private fb: FormBuilder, 
              private dialogService: NbDialogService, 
              private route: ActivatedRoute,
              private router: Router,
              private datePipe: DatePipe, 
              private connectionService: ApiConnectionService) {}

  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {
        this.error = false;
        this.formIsLoaded = false;
        this.documentId = params['id'];
        forkJoin([this.connectionService.get('document_types'), this.connectionService.get('issuers')]).subscribe({
          next: (results: any) => {
            this.documentTypes = results[0].data;
            this.issuers = results[1].data;
            if(this.documentId){  
              this.connectionService.get('documents', this.documentId, {headers: {accept: 'application/json'}}).subscribe({
                next: (res: any) => {
                  this.initializeForm(res.data);
                },
                error: e => {
                  this.errorHandler(e, '/')
                }
              })
            } else {
              this.initializeForm();
            }
          },
          error: e => {
            this.errorHandler(e, '/');
          }
        });    
    });
    
  }

  private initializeForm(data?: any){
    this.form = this.fb.group({
      name: ['Nuevo documento'],
      documentTypeId: ['', Validators.required],
      number: ['', Validators.required],
      issuerId: ['', Validators.required],
      issueDate: ['', Validators.required],
      issuePlace: ['', Validators.required],    
      subject: ['', Validators.required],    
      destinatary: ['', Validators.required],    
      adReferendum: [false, Validators.required],    
      body: this.fb.group({})
    });
    if(data){
      for(let [key, value] of Object.entries(data)) {
        if(key == 'body'){ 
          for (let [key, value] of Object.entries(data.body)) {
            if(Array.isArray(value)){
              let formArray = this.fb.array([]);
              this.body.addControl(key, formArray);            
              value?.forEach((elem) => formArray.push(this.fb.control(elem)));
            } else {
              this.body.addControl(key, this.fb.control(value));
            }
          }
        } else if(key == "issueDate"){
          this.form.get('issueDate')?.setValue(new Date (value + 'T00:00:00-03:00'));
        } else if(key != 'id'){
          this.form.get(key)?.setValue(value);
        }
      }
    }
    this.formIsLoaded = true;
  }

  get documentTypeId() {
    return this.form.get('documentTypeId')?.value;
  }

  get body(){
    return this.form.get('body') as FormGroup;
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

  documentTypeOnChange(documentTypeId: any){
    this.initializeForm();
    this.form.get('documentTypeId')?.setValue(documentTypeId); 
    if([1, 2, 3].includes(documentTypeId)){ //1: resolucion, 2: declaracion, 3: disposicion
      this.body.addControl('visto', this.fb.control(''));
      this.body.addControl('considerando', this.fb.array(['']));
      this.body.addControl('articulos', this.fb.array(['']));
    }
    if([4, 5, 6].includes(this.documentTypeId)){ //4: acta, 5: memo, 6: nota
      this.body.addControl('cuerpo', this.fb.control(''));
    }
    this.setDocumentName();    
  }

  adReferendumOnChange(){
    let adReferendum = this.form.get('adReferendum')?.value;
    this.form.get('adReferendum')?.setValue(!adReferendum);  
  }

  setDocumentName() {
    let documentTypesAbbreviatures: any = {
      1: 'resol',
      2: 'dec',
      3: 'disp',
      4: 'acta',
      5: 'memo',
      6: 'nota'
    };
    let documentTypeAbbreviature = documentTypesAbbreviatures[this.documentTypeId];
    let documentNumber = this.form.get('number')?.value;
    let currentYear = new Date().getFullYear()
    if(documentNumber){
      console.log(documentNumber);
      this.form.get('name')?.setValue(`${documentTypeAbbreviature}_${this.padLeft(documentNumber.toString(), 3)}_${currentYear}`);
    } else {
      this.form.get('name')?.setValue(`${documentTypeAbbreviature}_000_${currentYear}`);
    }
    
  }

  private padLeft(num: string, size:number) {
    while (num.length < size){
      num = "0" + num;
    } 
    return num;
}

  getBodyFormArray(formControlName: string) {
    return this.body.get(formControlName) as FormArray;
  }

  addItemToBodyFormArray(item: any, formArrayName: string) {
    console.log(this.form.value);
    (this.body.get(formArrayName) as FormArray).push(this.fb.control(item));
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
    this.dialogService.open(this.errorDialog, {context:{errorMsg: errorMsg}})
    .onClose.subscribe(_ => {
      if(urlRedirect){
        this.router.navigateByUrl(urlRedirect);
      }
    });
  }

  submit() {
    this.error = false;
    let data = this.form.value;
    this.actionResult = '';
    data.issueDate = this.datePipe.transform(this.form.get('issueDate')?.value, 'yyyy-MM-dd');
    this.submitting = true;
    if(!this.documentId){
      this.connectionService.post('documents', data)
      .pipe(
        finalize(() => this.submitting = false)
      )
      .subscribe({
        next: (res: any) => {
          this.documentId = res.data.id;
          this.actionResult = 'Guardado!';
        },
        error: e => {
          this.errorHandler(e);
          console.log(e);
        }
      })
    } else {
        this.connectionService.patch('documents', this.documentId, data)
        .pipe(
          finalize(() => this.submitting = false)
        )
        .subscribe({
          next: _ => {
            this.actionResult = 'Guardado!';
          },
          error: e => {
            this.errorHandler(e)
          }
        })
    }
  }

  export(){
    this.actionResult = '';
    this.error = false;
    this.submitting = true;
    this.connectionService.get('documents', this.documentId, {headers: {accept:'application/pdf'}, responseType: 'blob', observe: 'response'})
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
        this.errorHandler(e);
      }
    })
  }

}