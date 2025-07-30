import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NbAutocompleteModule, NbButtonModule, NbCardModule, NbContextMenuModule, NbDialogModule, NbIconModule, NbInputModule, NbListModule, NbPopoverModule, NbSelectModule, NbSpinnerModule, NbTooltipModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SharedModule } from '../../shared/shared.module';
import { DocumentSharedAccessComponent } from './document-shared-access.component';
import { UserSelectorComponent } from './user-selector/user-selector.component';



@NgModule({
  declarations: [
    DocumentSharedAccessComponent,
    UserSelectorComponent
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