import { Component, OnInit, ViewChild } from '@angular/core';
import { NbDialogService, NbMenuService } from '@nebular/theme';
import { EditUserDialogComponent } from '../../shared/edit-user-dialog/edit-user-dialog.component';
import { SignupInvitationsTabComponent } from './signup-invitations-tab/signup-invitations-tab.component';
import { filter, Subscription } from 'rxjs';
import { EditGroupDialogComponent } from './edit-group-dialog/edit-group-dialog.component';



@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {

  @ViewChild('invitationsTab') invitationsTab?: SignupInvitationsTabComponent;
  addOptionsMenuSubscription!: Subscription;
  addOptions = [
    { title: 'Crear cuenta' },
    { title: 'Crear grupo' }
  ];
  

  constructor(
    private dialogService: NbDialogService,
    private nbMenuService: NbMenuService, 
  ) {}

  ngOnInit(): void {
    this.addOptionsMenuSubscription = this.nbMenuService.onItemClick()
      .pipe(
        filter(({ tag }) => tag === 'add-options'),
      )
      .subscribe((event) => {
        if (event.item.title === 'Crear cuenta') {
          this.addUser();
        } else if (event.item.title === 'Crear grupo') {
          this.addGroup();
        }
      });
  }

  addUser() {
    this.dialogService.open(EditUserDialogComponent, {
      closeOnBackdropClick: false
    }).onClose.subscribe((success: boolean) => {
      if (success) {
        this.invitationsTab?.loadInvitations();
      }
    });
  }

  addGroup() {
    this.dialogService.open(EditGroupDialogComponent, {
      closeOnBackdropClick: false
    }).onClose.subscribe((success: boolean) => {
      if (success) {
        //this.invitationsTab?.loadInvitations();
      }
    });
  }

}