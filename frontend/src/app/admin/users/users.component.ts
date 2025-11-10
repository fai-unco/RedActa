import { Component, OnInit, ViewChild } from '@angular/core';
import { NbDialogService } from '@nebular/theme';
import { EditUserDialogComponent } from '../../shared/edit-user-dialog/edit-user-dialog.component';
import { SignupInvitationsTabComponent } from './signup-invitations-tab/signup-invitations-tab.component';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {

  @ViewChild('invitationsTab') invitationsTab?: SignupInvitationsTabComponent;

  constructor(
    private dialogService: NbDialogService,
  ) {}

  ngOnInit(): void {}

  addUser() {
    this.dialogService.open(EditUserDialogComponent, {
      closeOnBackdropClick: false
    }).onClose.subscribe((success: boolean) => {
      if (success) {
        this.invitationsTab?.loadInvitations();
      }
    });
  }

}