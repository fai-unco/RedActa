import { Component, OnInit, Input } from '@angular/core';
import { NbDialogRef } from '@nebular/theme';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';
import { Observable, of, forkJoin, concat } from 'rxjs';
import { startWith, debounceTime, map, catchError, finalize, switchMap } from 'rxjs/operators';

@Component({
  selector: 'app-edit-group-dialog',
  templateUrl: './edit-group-dialog.component.html',
  styleUrls: ['./edit-group-dialog.component.scss']
})
export class EditGroupDialogComponent implements OnInit {

  @Input() group: any | null = null;
  form!: FormGroup;
  loading = false;
  success = false;

  users: any[] = [];
  filteredUsers$!: Observable<any[]>;
  groupMembers: any[] = [];
  addedUsers: any[] = [];
  removedUsers: any[] = [];

  constructor(
    protected dialogRef: NbDialogRef<EditGroupDialogComponent>,
    private fb: FormBuilder,
    private api: ApiConnectionService,
    private errorHandler: ErrorHandlerService
  ) { }

  ngOnInit(): void {
    this.form = this.fb.group({
      name: ['', [Validators.required, Validators.maxLength(255)]],
      memberInput: ['']
    });
    if (this.group) {
      this.form.get('name')?.setValue(this.group.name ?? '');
      const members = this.group.redactaUsers ?? this.group.members ?? this.group.users ?? [];
      this.groupMembers = Array.isArray(members) ? members.slice() : [];
    }
    this.loadUsers();
    this.filteredUsers$ = this.form.get('memberInput')!.valueChanges.pipe(
      startWith(''),
      debounceTime(150),
      map(value => this.filterUsers(value))
    );
  }

  loadUsers(): void {
    this.loading = true;
    this.api.get('redacta_users')
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: (res: any) => this.users = res.data,
        error: e => this.errorHandler.handle(e)
      })
  }

  private filterUsers(value: any): any[] {
    const term = typeof value === 'string' ? value : (value ? (value.name ?? value.email ?? '') : '');
    if (!term) return this.users.slice(0, 20).filter(u => !this.groupMembers.find(s => s.id === u.id));
    const t = term.toLowerCase();
    return this.users.filter(u =>
      ((u.name || '') + ' ' + (u.lastName || '')).toLowerCase().includes(t) ||
      (u.email || '').toLowerCase().includes(t)
    ).filter(u => !this.groupMembers.find(s => s.id === u.id)).slice(0, 50);
  }

  displayUser(u: any): string {
    if (!u) return '';
    return typeof u === 'string' ? u : `${u.name ?? ''} ${u.lastName ?? ''} <${u.email}>`;
  }

  selectUser(user: any) {
    if (this.removedUsers.find(u => u.id === user.id)) {
      this.removedUsers = this.removedUsers.filter(u => u.id !== user.id);
    } else {
      this.addedUsers.push(user);
    }
    this.groupMembers.push(user);
    this.form.get('memberInput')!.setValue('');
  }

  removeSelectedUser(user: any) {
    this.groupMembers = this.groupMembers.filter(u => u.id !== user.id);
    this.removedUsers.push(user);
  }

  submit() {
    this.loading = true;
    const namePayload = { name: this.form.get('name')!.value };

    const deleteRequests = this.removedUsers.map(u =>
      this.api.delete('group_memberships', u.groupMembership.id).pipe(catchError(() => of(null)))
    );

    let groupRequest: Observable<any>;
    if (this.group == null) {
      groupRequest = this.api.post('groups', namePayload);
    } else if (this.group.name !== namePayload.name) {
      groupRequest = this.api.patch('groups', this.group.id, namePayload);
    } else {
      groupRequest = of(null); 
    }

    groupRequest
      .pipe(
        switchMap((res: any) => {
          if (res && this.group == null) {
            this.group = res.data;
          }
          return deleteRequests.length ? forkJoin(deleteRequests) : of(null);
        }),
        switchMap(() => {
          const postRequests = this.addedUsers.map(u =>
            this.api.post('group_memberships', { groupId: this.group!.id, redacta_user_id: u.id })
          );
          return postRequests.length ? forkJoin(postRequests) : of(null);
        }),
        finalize(() => this.loading = false)
      )
      .subscribe({
        next: _ => {
          this.success = true;
          setTimeout(() => this.close(), 2000);
        },
        error: e => this.errorHandler.handle(e)
      });
  }
  
  close() {
    this.dialogRef.close(this.success);
  }
}