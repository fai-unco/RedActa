import { Component, Input, OnInit } from '@angular/core';
import { NbDialogRef } from '@nebular/theme';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from '../error-handler/error-handler.service';
import { finalize } from 'rxjs';

@Component({
  selector: 'app-confirm-dialog',
  templateUrl: './confirm-dialog.component.html',
  styleUrls: ['./confirm-dialog.component.scss']
})
export class ConfirmDialogComponent implements OnInit {

  @Input() message: string = '';
  @Input() submitType!: 'danger' | 'success';
  @Input() apiRoute: string = '';
  @Input() resourceId: string = '';
  @Input() submitBtnLabel: string = '';
  @Input() requestType!: 'delete' | 'update';
  @Input() nestedApiResource: string = '';
  loading: boolean = false;
  success: boolean = false;

  constructor(
      protected dialogRef: NbDialogRef<ConfirmDialogComponent>,
      private api: ApiConnectionService,
      private errorHandler: ErrorHandlerService
    ) {}
  
    ngOnInit(): void {}
  
    confirm() {
      this.loading = true;
      let request;
      if (this.requestType == 'delete') {
        request = this.api.delete(this.apiRoute, this.resourceId)
      } else {
        request = this.api.patch(this.apiRoute, Number(this.resourceId), null, this.nestedApiResource)
      }
      request
        .pipe(finalize(() => this.loading = false))
        .subscribe({
          next: () => {
            this.success = true;
            setTimeout(() => this.close(), 4000);
          },
          error: (e) => this.errorHandler.handle(e)
        });
    }
  
    cancel() {
      this.dialogRef.close(false);
    }
  
    close() {
      this.dialogRef.close(this.success);
    }

}
