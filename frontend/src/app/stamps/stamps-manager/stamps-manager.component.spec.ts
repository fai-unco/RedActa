import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StampsManagerComponent } from './stamps-manager.component';

describe('StampsManagerComponent', () => {
  let component: StampsManagerComponent;
  let fixture: ComponentFixture<StampsManagerComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ StampsManagerComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(StampsManagerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
