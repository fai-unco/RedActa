import { Component, OnInit } from '@angular/core';
import { NbDialogRef } from '@nebular/theme';

@Component({
  selector: 'app-delete-dialog',
  templateUrl: './delete-dialog.component.html',
  styleUrls: ['./delete-dialog.component.scss']
})
export class DeleteDialogComponent implements OnInit {

  constructor(protected ref: NbDialogRef<DeleteDialogComponent>) { }

  ngOnInit(): void {
  }

  dismiss(remove: boolean){
    this.ref.close(remove);
  }

}
