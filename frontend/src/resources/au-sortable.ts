import { bindable, customAttribute, INode } from 'aurelia';

import Sortable, { SortableEvent } from 'sortablejs';

@customAttribute('au-sortable')
export class AuSortable {
    private sortable;
    private defaultOptions = {
        onChoose: e => this.trigger('sortable-choose', e),
        onUnchoose: e => this.trigger('sortable-unchoose', e),
        onStart: e => this.trigger('sortable-start', e),
        onEnd: e => this.trigger('sortable-end', e),
        onAdd: e => this.trigger('sortable-add', e),
        onUpdate: e => this.trigger('sortable-update', e),
        onRemove: e => this.trigger('sortable-remove', e),
        onSort: e => this.trigger('sortable-sort', e),
        onFilter: e => this.trigger('sortable-filter', e),
        onMove: e => this.trigger('sortable-move', e),
        onClone: e => this.trigger('sortable-clone', e),
        onChange: e => this.trigger('sortable-change', e)
    };

    @bindable offset: number;

    constructor(@INode readonly element: Element) {

    }

    public attached(): void {
        if (!this.sortable) {
            this.sortable = Sortable.create(this.element as HTMLElement, this.defaultOptions);
        }
    }

    public bind() {
        this.valueChanged();
    }

    public detached(): void {
        this.sortable.destroy();
    }

    public valueChanged(options = {}): void {
        this.sortable = Sortable.create(this.element as HTMLElement, {...options, ...this.defaultOptions});
    }

    private trigger(eventName: string, event: SortableEvent) {
        if (this.offset) {
            event.newIndex += this.offset;
            event.oldIndex += this.offset;
        }

        const customEvent = new CustomEvent(eventName, {
            bubbles: true,
            detail: event
        });

        this.element.dispatchEvent(customEvent);
    }
}