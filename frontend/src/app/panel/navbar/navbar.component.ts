import { Component, OnInit } from '@angular/core';
import { NbSidebarService, NbThemeService } from '@nebular/theme';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit {

  sidebarIsCollapsed = false;
  userMenu = [ { title: 'Cerrar sesión' } ];
 
  constructor(private sidebarService: NbSidebarService, private themeService: NbThemeService) { }

  ngOnInit() {
  }

  toggleSidebar(){
    if (this.sidebarIsCollapsed){
      this.sidebarService.expand('sidebar')
    } else {
      this.sidebarService.collapse('sidebar')
    }
    this.sidebarIsCollapsed = !this.sidebarIsCollapsed;
  }

  changeTheme(theme: string){
    this.themeService.changeTheme(theme);
  }
  
}
