import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DocumentEditorRoutingModule } from './document-editor-routing.module';
import { DocumentEditorComponent } from './document-editor.component';
import { NbInputModule, NbCardModule, NbButtonModule, NbActionsModule, NbUserModule, NbCheckboxModule, NbRadioModule, NbDatepickerModule, NbSelectModule, NbIconModule, NbPopoverModule, NbDialogModule, NbTooltipModule, NbSpinnerModule } from '@nebular/theme';
import { TextEditorComponent } from './text-editor/text-editor.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NbMomentDateModule } from '@nebular/moment';


@NgModule({
  declarations: [
    DocumentEditorComponent,
    TextEditorComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    DocumentEditorRoutingModule,
    NbInputModule,
    NbCardModule,
    NbButtonModule,
    NbActionsModule,
    NbUserModule,
    NbCheckboxModule,
    NbRadioModule,
    NbDatepickerModule,
    NbSelectModule,
    NbIconModule,
    NbDialogModule.forChild(),
    NbTooltipModule,
    NbMomentDateModule,
    NbSpinnerModule,
  ]
})
export class DocumentEditorModule { }
