import { Component, EventEmitter, Input, OnInit, Output, SimpleChanges } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { NbDialogService } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorDialogComponent } from 'src/app/shared/error-dialog/error-dialog.component';

@Component({
  selector: 'app-anexo',
  templateUrl: './anexo.component.html',
  styleUrls: ['./anexo.component.scss']
})
export class AnexoComponent implements OnInit {

  @Input('form') form!: any ;
  @Input('file') file!: any;
  uploading: boolean = false;
  @Output('onDelete') delete = new EventEmitter();
  @Input('index') index!: any;

  

  constructor(private apiConnectionService: ApiConnectionService, 
              private fb: FormBuilder,
              private dialogService: NbDialogService, 
              private router: Router) { }

  ngOnInit(): void {}

  ngOnChanges(changes: SimpleChanges) {
    if (changes['index']) {
      this.form.get('index').setValue(this.index);
    }
  }

  contentSourceOnChange(){
    this.form.get('fileId')!.reset();
    this.form.get('content')!.reset();
  }

  onFileSelected(event: any) {
    this.uploading = true;
    const selectedFile: File = event.target.files[0];
    if(selectedFile) {
      const formData = new FormData();
      formData.append("file", selectedFile);
      this.apiConnectionService.post('files', formData)
      .pipe(
        finalize(() => this.uploading = false)
      )
      .subscribe({
        next: (res: any) => {
          this.file = res.data;
          this.form.get('fileId')!.setValue(res.data.id);
        },
        error: e => {
          this.errorHandler(e)
        }
      });
    }
  }

  deleteFile(){
    this.uploading = true;
    this.apiConnectionService.delete('files', this.form.get('fileId').value)
    .pipe(
      finalize(() => this.uploading = false)
    )
    .subscribe({
      next: (res: any) => {
        this.file = null;
        this.form.get('fileId')!.reset();
      },
      error: e => {
        this.errorHandler(e)
      }
    })
    
  }

  deleteAnexo(){
    this.delete.emit('');
  }

  private errorHandler(error?: any, urlRedirect?: any) {
    let message =  error.error.message ? error.error.message : 'Ha habido un error. Pruebe reintentar la operación'
    this.openErrorDialog(message, urlRedirect);
  }

  openErrorDialog (errorMsg: string, urlRedirect?: any){
    this.dialogService.open(ErrorDialogComponent, {
      context: {
        msg: errorMsg,
      },
    })
    .onClose.subscribe(_ => {
      if(urlRedirect){
        this.router.navigate([urlRedirect]);
      }
    });
  }


}
