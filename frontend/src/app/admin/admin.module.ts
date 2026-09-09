import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UsersComponent } from './users/users.component';
import { AdminRoutingModule } from './admin-routing.module';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { NbCardModule, NbButtonModule, NbInputModule, NbTableModule, NbListModule, NbIconModule, NbSpinnerModule, NbOptionModule, NbSelectModule, NbAccordionModule, NbTooltipModule, NbTabsetModule, NbAutocompleteModule, NbContextMenuModule } from '@nebular/theme';
import { AccountSearchTabComponent } from './users/account-search-tab/account-search-tab.component';
import { SharedModule } from '../shared/shared.module';
import { SignupInvitationsTabComponent } from './users/signup-invitations-tab/signup-invitations-tab.component';
import { IssuersComponent } from './issuers/issuers.component';
import { IssuerSettingsDialogComponent } from './issuers/issuer-settings-dialog/issuer-settings-dialog.component';
import { EditIssuerDialogComponent } from './issuers/edit-issuer-dialog/edit-issuer-dialog.component';
import { HeadingsComponent } from './headings/headings.component';
import { EditHeadingDialogComponent } from './headings/edit-heading-dialog/edit-heading-dialog.component';
import { GroupsTabComponent } from './users/groups-tab/groups-tab.component';
import { EditGroupDialogComponent } from './users/edit-group-dialog/edit-group-dialog.component';
import { AddSignupInvitationComponent } from './users/add-signup-invitation/add-signup-invitation.component';

@NgModule({
  declarations: [
    UsersComponent,
    IssuersComponent,
    AccountSearchTabComponent,
    SignupInvitationsTabComponent,
    IssuerSettingsDialogComponent,
    EditIssuerDialogComponent,
    HeadingsComponent,
    EditHeadingDialogComponent,
    GroupsTabComponent,
    EditGroupDialogComponent,
    AddSignupInvitationComponent
  ],
  imports: [
    CommonModule,
    AdminRoutingModule,
    ReactiveFormsModule,
    FormsModule,
    NbCardModule,
    NbButtonModule,
    NbInputModule,
    NbTableModule,
    NbListModule,
    NbIconModule,
    NbSpinnerModule,
    NbOptionModule,
    NbSelectModule,
    NbAccordionModule,
    NbTooltipModule,
    NbTabsetModule,
    SharedModule,
    NbAutocompleteModule,
    NbContextMenuModule,
  ]
})
export class AdminModule { }