import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ItemSelectorComponent } from './item-selector.component';

describe('ItemSelectorComponent', () => {
  let component: ItemSelectorComponent;
  let fixture: ComponentFixture<ItemSelectorComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ItemSelectorComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ItemSelectorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
