import { Component, EventEmitter, Input, OnInit, Output, SimpleChanges } from '@angular/core';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

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
              private errorHandler: ErrorHandlerService) { }

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
          this.errorHandler.handle(e)
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
        this.errorHandler.handle(e)
      }
    })
  }

  deleteAnexo(){
    this.delete.emit('');
  }
}
