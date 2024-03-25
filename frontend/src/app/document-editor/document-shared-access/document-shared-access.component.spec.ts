import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DocumentSharedAccessComponent } from './document-shared-access.component';

describe('DocumentSharedAccessComponent', () => {
  let component: DocumentSharedAccessComponent;
  let fixture: ComponentFixture<DocumentSharedAccessComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DocumentSharedAccessComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DocumentSharedAccessComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
