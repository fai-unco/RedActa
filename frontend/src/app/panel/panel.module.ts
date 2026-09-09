import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PanelRoutingModule } from './panel-routing.module';
import { PanelComponent } from './panel.component';
import { NbActionsModule, NbButtonModule, NbContextMenuModule, NbDialogModule, NbIconModule, NbLayoutModule, NbMenuModule, NbSelectModule, NbSidebarModule, NbSpinnerModule, NbToggleModule, NbUserModule } from '@nebular/theme';
import { NbEvaIconsModule } from '@nebular/eva-icons';
import { SidebarMenuComponent } from './sidebar-menu/sidebar-menu.component';
import { NavbarComponent } from './navbar/navbar.component';
import { SharedModule } from '../shared/shared.module';

@NgModule({
  declarations: [
    PanelComponent,
    SidebarMenuComponent,
    NavbarComponent
  ],
  imports: [
    CommonModule,
    PanelRoutingModule,
    NbContextMenuModule,
    NbLayoutModule,
    NbEvaIconsModule,
    NbSidebarModule,
    NbMenuModule,
    NbEvaIconsModule,
    NbIconModule,
    NbActionsModule,
    NbUserModule,
    NbButtonModule,
    NbSelectModule,
    NbToggleModule,
    SharedModule,
    NbSpinnerModule
  ]
})
export class PanelModule { }
