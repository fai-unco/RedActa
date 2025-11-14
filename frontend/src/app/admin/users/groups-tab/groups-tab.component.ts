import { Component, OnInit } from '@angular/core';
import { NbDialogService } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { AddMemberDialogComponent } from './add-member-dialog/add-member-dialog.component';
import { UsersChangesNotifierService } from '../users-changes-notifier.service';
import { ConfirmDialogComponent } from 'src/app/shared/confirm-dialog/confirm-dialog.component';

@Component({
  selector: 'app-groups-tab',
  templateUrl: './groups-tab.component.html',
  styleUrls: ['./groups-tab.component.scss']
})
export class GroupsTabComponent implements OnInit {

  loading: boolean = true
  groups: any[] = []
  members: any[] = []
  selectedGroup: any = null;
  
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

  onGroupSelected(group: any) {
    this.members = group.redactaUsers;
    this.selectedGroup = group;
  }

  openAddMemberDialog() {
    this.dialogService.open(AddMemberDialogComponent, {
      context: { group: this.selectedGroup },
      closeOnBackdropClick: false
    }).onClose.subscribe((added: boolean) => {
      if (added) {
        this.loadGroups();
      }
    });
  }

  openRemoveMemberDialog(user:any) {
   this.loading = true;
    this.api.get(`group_memberships?adminMode=true&groupId=${this.selectedGroup.id}&redactaUserId=${user.id}`)
      .subscribe({
        next: (res: any) => {
          this.loading = false;
          this.dialogService.open(ConfirmDialogComponent, {
            context: { 
              message: `Confirma eliminar a <b>${user.name} ${user.lastName}</b> del grupo <b>${this.selectedGroup.name}</b>?`,
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

  openRemoveGroupDialog() {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma eliminar el grupo <b>${this.selectedGroup.name}</b>?`,
        submitType: 'danger',
        submitBtnLabel: 'Eliminar',
        apiRoute: 'groups',
        resourceId: this.selectedGroup.id,
        requestType: 'delete'
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((removed: boolean) => {
      if (removed) {
        this.loadGroups();
      }
    });
  }

  private loadGroups(): void {
    this.loading = true;
    this.api.get('groups?admin_mode=true')
      .pipe(finalize(() => (this.loading = false))) 
      .subscribe({
        next: (res: any) => {
          this.groups = res.data;
           //Get members of selected group from updated groups array using its id
            if (this.selectedGroup) {
              this.selectedGroup = this.groups.find(g => g.id === this.selectedGroup.id);
              this.members = this.selectedGroup ? this.selectedGroup.redactaUsers : [];
            }
        },
        error: err => this.errorHandler.handle(err)
      })
  }

}
