import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StampSelectorComponent } from './stamp-selector.component';

describe('StampSelectorComponent', () => {
  let component: StampSelectorComponent;
  let fixture: ComponentFixture<StampSelectorComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ StampSelectorComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(StampSelectorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
