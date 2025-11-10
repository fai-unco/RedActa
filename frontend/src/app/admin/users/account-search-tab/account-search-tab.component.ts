import { Component, OnInit } from '@angular/core';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { FormBuilder, FormGroup } from '@angular/forms';
import { NbDialogService } from '@nebular/theme';
import { EditUserDialogComponent } from 'src/app/shared/edit-user-dialog/edit-user-dialog.component';
import { finalize } from 'rxjs';
import { UsersChangesNotifierService } from '../users-changes-notifier.service';
import { ConfirmDialogComponent } from 'src/app/shared/confirm-dialog/confirm-dialog.component';

@Component({
  selector: 'app-account-search-tab',
  templateUrl: './account-search-tab.component.html',
  styleUrls: ['./account-search-tab.component.scss']
})
export class AccountSearchTabComponent implements OnInit {
  roles: any[] = [];
  resultsCardTitle: string = 'Todas las cuentas';
  searchForm!: FormGroup;
  users: any[] = [];
  loading: boolean = true;

  constructor(
    private api: ApiConnectionService,
    private errorHandler: ErrorHandlerService,
    private fb: FormBuilder,
    private dialogService: NbDialogService,
    private usersChangesNotifierService: UsersChangesNotifierService
  ) {}

  ngOnInit(): void {
    this.searchForm = this.fb.group({
      name: this.fb.control(''),
      lastName: this.fb.control(''),
      email: this.fb.control(''),
      role: this.fb.control(''),
      includeInactive: this.fb.control('false')
    });
    this.api.get('roles').subscribe({
      next: (res: any) => {
        this.roles = res.data;
      },
      error: e => this.errorHandler.handle(e)
    }); 
    this.seeAllUsers();
  }

  search() {
    this.loading = true;
    let queryString = 'adminMode=true&';
    Object.keys(this.searchForm.controls).forEach(key => {
      const value = this.searchForm.get(key)?.value;
      if (value) {
          queryString += `${key}=${value}&`;
      }
    });
    this.api.get(`redacta_users?${queryString}`)
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => {
          this.users = res.data;
          this.resultsCardTitle = `Resultados de búsqueda (${this.users.length})`;
        },
        error: e => this.errorHandler.handle(e)
      });
  }

  resetForm() {
    this.seeAllUsers();
    this.searchForm.reset();
  }

  editUser(user: any) {
    const dialogRef = this.dialogService.open(EditUserDialogComponent, {
      context: {
        user: user,
        allowRolesSelection: true,
        roles: this.roles
      },
      closeOnBackdropClick: false
    });
    dialogRef.onClose.subscribe(result => {
      if (result) {
        this.search();
        this.usersChangesNotifierService.notifier.next(1);
      }
    });
  }

  reactivateUser(user: any) {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma reactivar la cuenta de <b>${user.name} ${user.lastName}</b>?`,
        submitType: 'success',
        submitBtnLabel: 'Reactivar',
        apiRoute: 'redacta_users',
        resourceId: user.id,
        requestType: 'update',
        nestedApiResource: 'reactivate',
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((reactivate: boolean) => {
      if (reactivate) {
        this.search();
        this.usersChangesNotifierService.notifier.next(1);
      }
    });
  }

  removeUser(user: any) {
    this.dialogService.open(ConfirmDialogComponent, {
      context: { 
        message: `Confirma desactivar la cuenta de <b>${user.name} ${user.lastName}</b>?`,
        submitType: 'danger',
        submitBtnLabel: 'Desactivar',
        apiRoute: 'redacta_users',
        resourceId: user.id,
        requestType: 'delete'      
      },
      closeOnBackdropClick: false
    }).onClose.subscribe((removed: boolean) => {
      if (removed) {
        this.search();
        this.usersChangesNotifierService.notifier.next(1);
      }
    });
  }

  seeAllUsers () {
    this.loading = true;
    this.api.get('redacta_users?adminMode=true')
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => {
          this.users = res.data;
          this.resultsCardTitle = 'Todas las cuentas';
        },
        error: e => this.errorHandler.handle(e)
      });
  }
}
