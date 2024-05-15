import { Component, Input, OnInit } from '@angular/core';
import { NbDialogRef } from '@nebular/theme';
import { Observable, of } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from '../error-handler/error-handler.service';

@Component({
  selector: 'app-item-selector',
  templateUrl: './item-selector.component.html',
  styleUrls: ['./item-selector.component.scss']
})
export class ItemSelectorComponent implements OnInit {

  /*users: any [] = [];
  form!: FormGroup;
  documentId: any;
  viewState = 'loading';
  filteredUsers!: Observable<any[]>;
  username: string = '';*/
  @Input('items') items!: any [];
  @Input('filterBy') filterBy: any= '';
  filteredItems!: Observable<any[]>;
  @Input('itemName') itemName: string = '';
  @Input('apiRoute') apiRoute: string = '';
  @Input('autocomplete') autocomplete: boolean = false;
  item!: any;
  value: string = '';
  viewState = 'rendering';
  
  constructor(protected dialogRef: NbDialogRef<ItemSelectorComponent>,
              protected connectionService: ApiConnectionService,
              private errorHandler: ErrorHandlerService
    ) { }
 
  ngOnInit(): void {
    if (!this.items) {
      this.viewState = 'loading';
      this.connectionService.get(this.apiRoute)
        .subscribe({
          next: (res: any) => {
            this.items = res.data;
            this.viewState = 'rendering';
          },
          error: e => {
            this.errorHandler.handle(e);
            this.dialogRef.close();
          }
        });
    }
  }

  filter(filterString: any) {
    const filterValue = filterString.toLowerCase();
    return this.items.filter(item => (item[this.filterBy].toLowerCase()).normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(filterValue));
  }

  onUsernameChange(value: string) {
    this.filteredItems = of(this.filter(value));
  }

  selectItem(item: any) {
    this.item = item;
  }

  submit() {
    this.dialogRef.close(this.item);
    //this.dialogRef.close(this.items.findIndex(item => item.id == this.item.id));
  }

  cancel() {
    this.dialogRef.close();
  }

}
