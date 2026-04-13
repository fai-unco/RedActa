import { Component, OnInit } from '@angular/core';
import { NbDialogService } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { UsersChangesNotifierService } from '../users-changes-notifier.service';
import { ConfirmDialogComponent } from 'src/app/shared/confirm-dialog/confirm-dialog.component';
import { EditGroupDialogComponent } from '../edit-group-dialog/edit-group-dialog.component';

@Component({
  selector: 'app-groups-tab',
  templateUrl: './groups-tab.component.html',
  styleUrls: ['./groups-tab.component.scss']
})
export class GroupsTabComponent implements OnInit {

  loading: boolean = true
  groups: any[] = []
  members: any[] = []
  
  constructor(private api: ApiConnectionService,
              private errorHandler: ErrorHandlerService,
              private dialogService: NbDialogService,
              private usersChangesNotifierService: UsersChangesNotifierService) { }


  ngOnInit(): void {
    this.loadGroups();
    this.usersChangesNotifierService.notifier.subscribe(() => {
      this.loadGroups();
    })
  }


  openRemoveMemberDialog(user:any, group: any) {
   this.loading = true;
    this.api.get(`group_memberships?adminMode=true&groupId=${group.id}&redactaUserId=${user.id}`)
      .subscribe({
        next: (res: any) => {
          this.loading = false;
          this.dialogService.open(ConfirmDialogComponent, {
            context: { 
              message: `Confirma eliminar a <b>${user.name} ${user.lastName}</b> del grupo <b>${group.name}</b>?`,
              submitType: 'danger',
              submitBtnLabel: 'Eliminar',
              apiRoute: 'group_memberships',
              resourceId: res.data[0].id, 
              requestType: 'delete'
            },
            closeOnBackdropClick: false
          }).onClose.subscribe((removed: boolean) => {
            if (removed) {
              this.loadGroups();
            }
          });
        },
        error: err => {
          this.loading = false;
          this.errorHandler.handle(err);
        }
      });
    
  }

  openRemoveGroupDialog(group: any) {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma eliminar el grupo <b>${group.name}</b>?`,
        submitType: 'danger',
        submitBtnLabel: 'Eliminar',
        apiRoute: 'groups',
        resourceId: group.id,
        requestType: 'delete'
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((removed: boolean) => {
      if (removed) {
        this.loadGroups();
      }
    });
  }

  loadGroups(): void {
    this.loading = true;
    this.api.get('groups?viewAll=true')
      .pipe(finalize(() => (this.loading = false))) 
      .subscribe({
        next: (res: any) => {
          this.groups = res.data;
        },
        error: err => this.errorHandler.handle(err)
      })
  }

  openEditGroupDialog(group: any = null) {
    this.dialogService.open(EditGroupDialogComponent, {
      context: { group },
      closeOnBackdropClick: false
    }).onClose.subscribe((success: boolean) => {
      if (success) {
        this.loadGroups();
      }
    });
  }

}
