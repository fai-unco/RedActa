import { Component, OnInit } from '@angular/core';
import { NbSidebarService } from '@nebular/theme';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit {

  sidebarIsCollapsed = false;
  
  constructor(private sidebarService: NbSidebarService) { }

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
}
