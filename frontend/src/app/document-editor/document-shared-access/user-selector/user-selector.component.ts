import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { NbDialogRef } from '@nebular/theme';
import { finalize, forkJoin } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-user-selector',
  templateUrl: './user-selector.component.html',
  styleUrls: ['./user-selector.component.scss']
})
export class UserSelectorComponent implements OnInit {

  users: any [] = [];
  form!: FormGroup;
  documentId: any;
  viewState = 'loading';

  constructor(private connectionService: ApiConnectionService,
    protected dialogRef: NbDialogRef<UserSelectorComponent>,
    private fb: FormBuilder,
    private errorHandler: ErrorHandlerService) { }
 
  ngOnInit(): void {
    this.form = this.fb.group({
      documentId: this.fb.control(''),
      redactaUserId: this.fb.control('')
    });
    let requests = [this.connectionService.get('redacta_users')];
    this.form.get('documentId')?.setValue(this.documentId);
    
    forkJoin(requests)
      .pipe(finalize(() => {this.viewState = 'rendering'}))
      .subscribe({
        next: (res: any) => {
          this.users = res[0].data;
          if(res.length > 1){
            this.form.get('redactaUserId')?.setValue(res[1].data.redactaUserId);
            this.form.get('documentId')?.setValue(res[1].data.documentId);
          }
        },
        error: e => {
          this.errorHandler.handle(e);
          this.cancel();
        }
      });
  }

  get userIdFormControl(){
    return this.form.get('redactaUserId') as FormControl;
  }

  submit(){
    let request;
    this.viewState = 'loading';
    request = this.connectionService.post('documents_shared_accesses', this.form.value);
    request
      .pipe(finalize(() => {this.viewState = 'rendering'}))
      .subscribe({
        next: _ => {
          this.dialogRef.close(true);
        },
        error: e => {
          this.errorHandler.handle(e);
        }
      })
  }

  cancel(){
    this.dialogRef.close(false);
  }

}
