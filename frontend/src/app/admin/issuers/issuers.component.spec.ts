import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IssuersComponent } from './issuers.component';

describe('IssuersComponent', () => {
  let component: IssuersComponent;
  let fixture: ComponentFixture<IssuersComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ IssuersComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(IssuersComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
