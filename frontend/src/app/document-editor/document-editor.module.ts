import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DocumentEditorRoutingModule } from './document-editor-routing.module';
import { DocumentEditorComponent } from './document-editor.component';
import { NbInputModule, NbCardModule, NbButtonModule, NbActionsModule, NbUserModule, NbCheckboxModule, NbRadioModule, NbDatepickerModule, NbSelectModule, NbIconModule, NbTooltipModule, NbSpinnerModule, NbDialogModule, NbContextMenuModule, NbTabsetModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NbMomentDateModule } from '@nebular/moment';
import { SharedModule } from '../shared/shared.module';
import { DocumentContentModule } from './document-content/document-content.module';
import { DocumentStampsModule } from './document-stamps/document-stamps.module';
import { DocumentSharedAccessModule } from './document-shared-access/document-shared-access.module';



@NgModule({
  declarations: [
    DocumentEditorComponent,
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
    NbTooltipModule,
    NbMomentDateModule,
    NbSpinnerModule,
    NbDialogModule.forChild(),
    NbContextMenuModule,
    SharedModule,
    NbTabsetModule,
    DocumentStampsModule,
    DocumentContentModule,
    DocumentSharedAccessModule
  ]
})
export class DocumentEditorModule { }
