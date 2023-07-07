import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentFinderComponent } from './document-finder.component';

describe('DocumentFinderComponent', () => {
  let component: DocumentFinderComponent;
  let fixture: ComponentFixture<DocumentFinderComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DocumentFinderComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentFinderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
