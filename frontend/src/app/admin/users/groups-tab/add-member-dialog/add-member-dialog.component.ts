import { Component, Input, OnInit } from '@angular/core';
import { NbDialogRef } from '@nebular/theme';
import { FormControl, FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { Observable, of } from 'rxjs';
import { finalize } from 'rxjs/operators';

@Component({
  selector: 'app-add-member-dialog',
  templateUrl: './add-member-dialog.component.html',
  styleUrls: ['./add-member-dialog.component.scss']
})
export class AddMemberDialogComponent implements OnInit {

  @Input() group: any;
  success: boolean = false;
  loading: boolean = true;
  users: any[] = [];
  filteredUsers$!: Observable<any[]>;
  form!: FormGroup;
  userInputValue: any = null;

  constructor(
    protected dialogRef: NbDialogRef<AddMemberDialogComponent>,
    private fb: FormBuilder,
    private api: ApiConnectionService,
    private errorHandler: ErrorHandlerService
  ) { }

  ngOnInit(): void {
    this.form = this.fb.group({
      redactaUserId: ['', Validators.required],
      groupId: [this.group.id, Validators.required]
    });
    this.loadUsers();
    this.filteredUsers$ = of(this.users);
  }

  userIdFC () {
    return this.form.get('redactaUserId') as FormControl;
  }

  loadUsers(): void {
    this.loading = true;
    this.api.get('redacta_users')
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => this.users = res?.data,
        error: err => this.errorHandler.handle(err),
      });
  }

  private filterUsers(value: any): any[] {
    const term = typeof value === 'string' ? value : (value ? (value.name ?? value.email ?? '') : '');
    if (!term) {
      //show first 20 users if no term
      return this.users.slice(0, 20);
    }
    const t = term.toLowerCase();
    return this.users.filter(u =>
      (u.name + ' ' + u.lastName || '').toLowerCase().includes(t) ||
      (u.email || '').toLowerCase().includes(t)
    ).slice(0, 50);
  }

  displayUser(userId: any): string {
    //Find user by id in users array
    const user = this.users.find(u => u.id == userId);
    return user ? `${user.name ?? ''} ${user.lastName ?? ''}` : '';
  }

  close() {
    this.dialogRef.close(this.success);
  }

  onUserInputValueChange(user: any) {
    this.form.get('redactaUserId')!.setValue(null)
    this.filteredUsers$ = of(this.filterUsers(user));
  }

  selectUser(user:any) {
    this.form.get('redactaUserId')!.setValue(user.id);
  }

  submit() {
    this.loading = true;
    this.api.post('group_memberships', this.form.value)
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: _ => {
          this.success = true;
          setTimeout(() => this.close(), 4000);
        },
        error: e => this.errorHandler.handle(e),
      });
  }

}
