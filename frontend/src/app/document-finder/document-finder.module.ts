import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DocumentFinderComponent } from './document-finder.component';
import { DocumentFinderRoutingModule } from './document-finder-routing.module';
import { NbButtonModule, NbCardModule, NbDatepickerModule, NbInputModule, NbListModule, NbSelectModule, NbSpinnerModule } from '@nebular/theme';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NbMomentDateModule } from '@nebular/moment';



@NgModule({
  declarations: [
    DocumentFinderComponent
  ],
  imports: [
    CommonModule, 
    FormsModule,
    ReactiveFormsModule,
    DocumentFinderRoutingModule,
    NbInputModule,
    NbCardModule,
    NbButtonModule,
    NbListModule,
    NbSpinnerModule,
    NbDatepickerModule,
    NbMomentDateModule,
    NbSelectModule,

  ]
})
export class DocumentFinderModule { }
