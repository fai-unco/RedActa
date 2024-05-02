import { Component, EventEmitter, Input, OnDestroy, OnInit, Output, SimpleChanges } from '@angular/core';
import { NbMenuService } from '@nebular/theme';
import { Subscription, filter, finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-anexo',
  templateUrl: './anexo.component.html',
  styleUrls: ['./anexo.component.scss']
})
export class AnexoComponent implements OnInit, OnDestroy {

  @Input('form') form!: any ;
  @Input('file') file!: any;
  uploading: boolean = false;
  @Output('onDelete') delete = new EventEmitter();
  @Input('index') index!: any;
  @Output('onInsert') insert = new EventEmitter();
  menuSubscription!: Subscription;
  listItemActions: any;
  @Input() 
  set isAnexoUnico(value: boolean) {
    if (value) {
      this.setListItemActions(false);
    } else {
      this.setListItemActions();
    }
  };

  constructor(private apiConnectionService: ApiConnectionService, 
              private errorHandler: ErrorHandlerService,
              private nbMenuService: NbMenuService) { }

  ngOnInit(): void {
    if (this.index > 8 || this.isAnexoUnico) {
      this.setListItemActions(false);
    } else {
      this.setListItemActions();
    }
    this.menuSubscription = this.nbMenuService.onItemClick().pipe(
      filter (({ tag }) => tag == 'anexo-menu-' + this.index),
    ).subscribe ((event: any) => {
      let action = event.item.title;
      if (action == 'Eliminar'){
        this.delete.emit('');
      } else if (action == 'Agregar 1 arriba') {
        this.insert.emit(this.index);
      } else {
        this.insert.emit(this.index + 1);
      }
    });
  }

  ngOnChanges(changes: SimpleChanges) {
    if (changes['index']) {
      this.form.get('index').setValue(this.index);
    }
  }

  ngOnDestroy (){
    this.menuSubscription.unsubscribe();
  }

  private setListItemActions(setFullList: boolean = true) {
    if (setFullList) {
      this.listItemActions = [
        { title: 'Agregar 1 arriba' }, 
        { title: 'Agregar 1 abajo' },
        { title: 'Eliminar' }
      ];
    } else {
      this.listItemActions = [{ title: 'Eliminar' }];
    }
  }

  contentSourceOnChange(){
    this.form.get('fileId')!.reset();
    this.form.get('content')!.reset();
  }

  onFileSelected(event: any) {
    this.uploading = true;
    const selectedFile: File = event.target.files[0];
    if(selectedFile) {
      const formData = new FormData();
      formData.append("file", selectedFile);
      this.apiConnectionService.post('files', formData)
      .pipe(
        finalize(() => this.uploading = false)
      )
      .subscribe({
        next: (res: any) => {
          this.file = res.data;
          this.form.get('fileId')!.setValue(res.data.id);
        },
        error: e => {
          this.errorHandler.handle(e)
        }
      });
    }
  }

  deleteFile(){
    this.uploading = true;
    this.apiConnectionService.delete('files', this.form.get('fileId').value)
    .pipe(
      finalize(() => this.uploading = false)
    )
    .subscribe({
      next: (res: any) => {
        this.file = null;
        this.form.get('fileId')!.reset();
      },
      error: e => {
        this.errorHandler.handle(e)
      }
    })
  }

  deleteAnexo(){
    this.delete.emit('');
  }
}
