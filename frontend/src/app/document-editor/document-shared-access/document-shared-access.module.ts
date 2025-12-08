import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NbAutocompleteModule, NbButtonModule, NbCardModule, NbContextMenuModule, NbDialogModule, NbIconModule, NbInputModule, NbListModule, NbPopoverModule, NbSelectModule, NbSpinnerModule, NbTooltipModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from '../../shared/shared.module';
import { DocumentSharedAccessComponent } from './document-shared-access.component';
import { EditSharedAccessComponent } from './edit-shared-access/edit-shared-access.component';



@NgModule({
  declarations: [
    DocumentSharedAccessComponent,
    EditSharedAccessComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    NbInputModule,
    NbCardModule,
    NbButtonModule,
    NbSelectModule,
    NbIconModule,
    NbSpinnerModule,
    NbDialogModule.forChild(),
    NbListModule,
    SharedModule,
    NbTooltipModule,
    NbContextMenuModule,
    NbAutocompleteModule,
    NbPopoverModule,
  ],
  exports: [
    DocumentSharedAccessComponent
  ]
})
export class DocumentSharedAccessModule { }