import { Component, Inject, OnDestroy, OnInit } from '@angular/core';
import { NB_WINDOW, NbMenuService, NbSidebarService, NbThemeService } from '@nebular/theme';
import { Subscription } from 'rxjs';
import { filter, map } from 'rxjs/operators';
import { AuthService } from 'src/app/auth/auth.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit, OnDestroy {

  sidebarIsCollapsed = false;
  userMenu = [ { 
    title: 'Cerrar sesión', 
  } ];
  menuSubscription!: Subscription;
 
  constructor(private sidebarService: NbSidebarService, 
    @Inject(NB_WINDOW) private window: any, 
    private nbMenuService: NbMenuService, 
    private themeService: NbThemeService,
    private authService: AuthService  
  ) { }

  ngOnInit() {
    this.menuSubscription = this.nbMenuService.onItemClick()
      .subscribe((event) => {
        if(event.item.title === 'Cerrar sesión'){
          this.authService.logout();
        }
      });
  }

  ngOnDestroy(): void {
    this.menuSubscription.unsubscribe();
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
