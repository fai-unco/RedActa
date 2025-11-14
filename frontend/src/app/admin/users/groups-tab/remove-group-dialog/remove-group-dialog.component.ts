import { Component, Input, OnInit } from '@angular/core';
import { NbDialogRef } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';


@Component({
  selector: 'app-remove-group-dialog',
  templateUrl: './remove-group-dialog.component.html',
  styleUrls: ['./remove-group-dialog.component.scss']
})
export class RemoveGroupDialogComponent implements OnInit {

  @Input() group: any;
  loading = false;
  removed = false;

  constructor(
    protected dialogRef: NbDialogRef<RemoveGroupDialogComponent>,
    private api: ApiConnectionService,
    private errorHandler: ErrorHandlerService
  ) {}

  ngOnInit(): void {}

  confirm() {
    this.loading = true;
    this.api.delete('groups', this.group.id)
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: () => {
          this.removed = true;
          setTimeout(() => this.close(), 4000);
        },
        error: (e) => this.errorHandler.handle(e)
      });
  }

  cancel() {
    this.dialogRef.close(false);
  }

  close() {
    this.dialogRef.close(this.removed);
  }
  

}
