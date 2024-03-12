import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'app-page-container',
  templateUrl: './page-container.component.html',
  styleUrls: ['./page-container.component.scss']
})
export class PageContainerComponent implements OnInit {

  @Input('viewState') viewState: string = '';
  @Input('title') title: string = '';
  @Input('actionResult') actionResult: string = '';

  constructor() { }

  ngOnInit(): void {
  }

}
