import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { NbDialogRef, NbDialogService } from '@nebular/theme';
import { finalize, forkJoin } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorDialogComponent } from 'src/app/shared/error-dialog/error-dialog.component';

@Component({
  selector: 'app-signature-selector',
  templateUrl: './signature-selector.component.html',
  styleUrls: ['./signature-selector.component.scss']
})
export class SignatureSelectorComponent implements OnInit {

  stamps: any [] = [];
  form!: FormGroup;
  @Input() documentId: any;
  @Input() signatureId: any;
  viewState = 'loading';

  constructor(private connectionService: ApiConnectionService,
    private dialogService: NbDialogService,
    protected dialogRef: NbDialogRef<SignatureSelectorComponent>,
    private router: Router,
    private fb: FormBuilder) { }
 
  ngOnInit(): void {
    this.form = this.fb.group({
      documentId: this.fb.control(''),
      stampId: this.fb.control('')
    });
    let requests = [this.connectionService.get('stamps')];
    if(this.signatureId){
      requests.push(this.connectionService.get(`signatures/${this.signatureId}`));
    } else {
      this.form.get('documentId')?.setValue(this.documentId);
    }
    forkJoin(requests)
      .pipe(finalize(() => {this.viewState = 'rendering'}))
      .subscribe({
        next: (res: any) => {
          this.stamps = res[0].data;
          if(res.length > 1){
            this.form.get('stampId')?.setValue(res[1].data.stampId);
            this.form.get('documentId')?.setValue(res[1].data.documentId);
          }
        },
        error: e => {
          this.errorHandler(e);
          this.cancel();
        }
      });
  }

  get stampIdFormControl(){
    return this.form.get('stampId') as FormControl;
  }

  submit(){
    let request;
    this.viewState = 'loading';
    if(!this.signatureId){
      request = this.connectionService.post('signatures', this.form.value);
    } else {
      request = this.connectionService.patch('signatures', this.signatureId, this.form.value);
    }
    request
      .pipe(finalize(() => {this.viewState = 'rendering'}))
      .subscribe({
        next: _ => {
          this.dialogRef.close(true);
        },
        error: e => {
          this.errorHandler(e);
        }
      })
  }

  cancel(){
    this.dialogRef.close(false);
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

}