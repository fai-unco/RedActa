import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NbDialogRef } from '@nebular/theme';
import { Observable, finalize, of } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-edit-shared-access',
  templateUrl: './edit-shared-access.component.html',
  styleUrls: ['./edit-shared-access.component.scss']
})
export class EditSharedAccessComponent implements OnInit {

  @Input() users: any;
  @Input() groups: any;
  @Input()documentId: any;
  form!: FormGroup;
  loading = false;
  filteredItems!: Observable<any[]>;
  accessType: string = 'user';
  items!: any [];
  success: boolean = false;
  @ViewChild('itemNameInput') itemNameInput: any;
  
  constructor(private connectionService: ApiConnectionService,
    protected dialogRef: NbDialogRef<EditSharedAccessComponent>,
    private fb: FormBuilder,
    private errorHandler: ErrorHandlerService) { }
 
  ngOnInit(): void {
    this.form = this.fb.group({
      documentId: this.fb.control(this.documentId, [Validators.required]),
      resourceId: this.fb.control('', [Validators.required])
    });
    this.items = this.users;
    this.onItemNameChange();
  }

  filter(filterString: string) {
    const filterValue = filterString? filterString.toLowerCase() : '';
    return this.items.filter((item: any) => (item.name.toLowerCase()).normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(filterValue));
  }

  onItemNameChange() {
    this.filteredItems = of(this.filter(this.itemNameInput? this.itemNameInput.nativeElement.value : ''));
    this.form.get('resourceId')?.setValue('');
  }

  selectItem(item: any) {
    this.form.controls['resourceId'].setValue(item.id);
    this.itemNameInput.nativeElement.value = item.name;
  }

  onAccessTypeChange(value: string) {
    this.accessType = value;
    this.items = value == 'user' ? this.users : this.groups;
    this.itemNameInput.nativeElement.value = '';
    this.onItemNameChange();
  }

  submit(){
    let request;
    this.loading = true;
    let requestBody = this.form.value;
    requestBody.resourceType = this.accessType == 'group'? 'group' : 'user';
    request = this.connectionService.post('document_shared_accesses', requestBody);
    request
      .pipe(finalize(() => this.loading = false))
      .subscribe({
        next: _ => {
          this.success = true;
          setTimeout(_ => this.close(), 2000);
        },
        error: e =>  this.errorHandler.handle(e)
      })
  }

  close() {
    this.dialogRef.close(this.success);
  }
}
