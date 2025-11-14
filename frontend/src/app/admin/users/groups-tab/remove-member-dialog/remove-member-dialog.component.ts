import { Component, Input, OnInit } from '@angular/core';
import { NbDialogRef } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-remove-member-dialog',
  templateUrl: './remove-member-dialog.component.html',
  styleUrls: ['./remove-member-dialog.component.scss']
})
export class RemoveMemberDialogComponent implements OnInit {

  @Input() group: any;
  @Input() user: any;
  loading = false;
  removed = false;
  membership: any = null;

  constructor(
    protected dialogRef: NbDialogRef<RemoveMemberDialogComponent>,
    private api: ApiConnectionService,
    private errorHandler: ErrorHandlerService
  ) {}

  ngOnInit(): void {
    this.api.get(`group_memberships?adminMode=true&groupId=${this.group.id}&redactaUserId=${this.user.id}`)
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => {
          this.membership = res.data[0];
        },
        error: err => this.errorHandler.handle(err),
      });
  }

  confirm() {
    this.loading = true;
    this.api.delete('group_memberships', this.membership.id)
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: () => {
          this.removed = true;
          setTimeout(() => this.close(), 4000);
        },
        error: (e) => this.errorHandler.handle(e)
      });
  }

  close() {
    this.dialogRef.close(this.removed);
  }

}




  

