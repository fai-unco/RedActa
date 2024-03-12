import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NbButtonModule, NbCardModule, NbDialogModule, NbIconModule, NbInputModule, NbListModule, NbSpinnerModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ErrorDialogComponent } from './error-dialog/error-dialog.component';
import { TextEditorComponent } from './text-editor/text-editor.component';
import { DeleteDialogComponent } from './delete-dialog/delete-dialog.component';
import { PageContainerComponent } from './page-container/page-container.component';


@NgModule({
  declarations: [
    TextEditorComponent,
    ErrorDialogComponent,
    DeleteDialogComponent,
    PageContainerComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    NbInputModule,
    NbCardModule,
    NbButtonModule,
    NbIconModule,
    NbSpinnerModule,
    NbListModule,
    NbDialogModule.forChild(),
  ],
  exports: [
    TextEditorComponent,
    ErrorDialogComponent,
    DeleteDialogComponent,
    PageContainerComponent
  ]
})
export class SharedModule { }
