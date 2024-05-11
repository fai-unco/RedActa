import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SignatureSelectorComponent } from './signature-selector.component';

describe('SignatureSelectorComponent', () => {
  let component: SignatureSelectorComponent;
  let fixture: ComponentFixture<SignatureSelectorComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SignatureSelectorComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SignatureSelectorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
