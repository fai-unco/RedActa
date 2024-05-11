import { Component, Input, OnInit } from '@angular/core';
import { NbDialogRef } from '@nebular/theme';
import { finalize } from 'rxjs';
import { ApiConnectionService } from 'src/app/api-connection.service';
import { ErrorHandlerService } from 'src/app/shared/error-handler/error-handler.service';

@Component({
  selector: 'app-stamp-selector',
  templateUrl: './stamp-selector.component.html',
  styleUrls: ['./stamp-selector.component.scss']
})
export class StampSelectorComponent implements OnInit {

  stamps: any [] = [];
  viewState = 'loading';
  selectedStampIndex!: number;

  constructor(private connectionService: ApiConnectionService,
    protected dialogRef: NbDialogRef<StampSelectorComponent>,
    private errorHandler: ErrorHandlerService) { }
 
  ngOnInit(): void {
    this.connectionService.get('stamps')
      .pipe(finalize(() => {this.viewState = 'rendering'}))
      .subscribe({
        next: (res: any) => {
          this.stamps = res.data;
        },
        error: e => {
          this.errorHandler.handle(e);
          this.close();
        }
      });
  }

  close(status = false) {
    let output = '';
    if (status) {
     output = this.stamps[this.selectedStampIndex].content;
    } 
    this.dialogRef.close(output);
  }

}