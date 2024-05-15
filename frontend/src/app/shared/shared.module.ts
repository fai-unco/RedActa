import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NbAutocompleteModule, NbButtonModule, NbCardModule, NbDialogModule, NbIconModule, NbInputModule, NbListModule, NbSelectModule, NbSpinnerModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ErrorDialogComponent } from './error-dialog/error-dialog.component';
import { TextEditorComponent } from './text-editor/text-editor.component';
import { DeleteDialogComponent } from './delete-dialog/delete-dialog.component';
import { PageContainerComponent } from './page-container/page-container.component';
import { EditorModule, TINYMCE_SCRIPT_SRC } from '@tinymce/tinymce-angular';
import { ItemSelectorComponent } from './item-selector/item-selector.component';

@NgModule({
  declarations: [
    TextEditorComponent,
    ErrorDialogComponent,
    DeleteDialogComponent,
    PageContainerComponent,
    ItemSelectorComponent
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
    EditorModule,
    NbSelectModule,
    NbAutocompleteModule,
  ],
  exports: [
    TextEditorComponent,
    ErrorDialogComponent,
    DeleteDialogComponent,
    PageContainerComponent,
    ItemSelectorComponent
  ],
  providers: [
    { provide: TINYMCE_SCRIPT_SRC, useValue: 'tinymce/tinymce.min.js' },
  ],
})
export class SharedModule { }
