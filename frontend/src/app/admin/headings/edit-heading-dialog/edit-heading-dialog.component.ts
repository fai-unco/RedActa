import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NbDialogRef } from '@nebular/theme';
import { finalize, Observable, of } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-edit-heading-dialog',
  templateUrl: './edit-heading-dialog.component.html',
  styleUrls: ['./edit-heading-dialog.component.scss']
})
export class EditHeadingDialogComponent implements OnInit {

  loading!: boolean;
  headingForm!: FormGroup;
  @Input() heading!: any;
  success: boolean = false;
  successMsg: string = '';
  @Input() issuers: any[] = [];
  issuerId!: any;
  file: any;
  fileHasBeenUploaded: boolean = false;
  deletedFileId!: any;


  constructor(protected api: ApiConnectionService,
              protected fb: FormBuilder,
              protected errorHandler: ErrorHandlerService,
              protected dialogRef: NbDialogRef<EditHeadingDialogComponent>) { }

  ngOnInit(): void {
    this.headingForm = this.fb.group({
      description: this.fb.control(this.heading? this.heading.description: '', [Validators.required]),
      issuerId: this.fb.control(this.heading? this.heading.issuerId : '', [Validators.required]),
      fileId: this.fb.control(this.heading && this.heading.file? this.heading.file.id : '', [Validators.required])
    })
    this.loading = false;
    this.file = this.heading && this.heading.file? this.heading.file : null;
    this.successMsg = this.heading ? 'Membrete actualizado exitosamente': 'Membrete creado existosamente';
  }

  close() {
    this.dialogRef.close(this.success);
  }

  selectIssuer(newIssuerId: any) {
    this.issuerId = newIssuerId;
    this.headingForm.get('issuerId')?.setValue(newIssuerId);
  }

  onFileSelected(event: any) {
    this.file = event.target.files[0];
    this.fileHasBeenUploaded = true;
  }

  deleteFile() {
    this.deletedFileId = this.headingForm.get('fileId')?.value;
    this.file = null;
  }

  submit() {
    this.loading = true;
    let headingData = this.headingForm.value;
    const sendHeadingRequest = (data: any) => {
      const request = this.heading
        ? this.api.patch('headings', this.heading.id, data)
        : this.api.post('headings', data);

      request
        .pipe(finalize(() => this.loading = false))
        .subscribe({
          next: _ => {
            this.success = true;
            setTimeout(() => this.close(), 4000);
          },
          error: e => this.errorHandler.handle(e)
        });
    };
    if (this.fileHasBeenUploaded) {
      const formData = new FormData();
      formData.append('file', this.file);
      this.api.post('files', formData)
        .subscribe({
          next: (res: any) => {
            headingData.fileId = res.data.id;
            sendHeadingRequest(headingData);
          },
          error: e => {
            this.loading = false;
            this.errorHandler.handle(e);
          }
        });
    } else {
      sendHeadingRequest(headingData);
    }
    if (this.deletedFileId) {
      this.api.delete('files', this.deletedFileId).subscribe();
    }
  }
}
