import type { Ref } from 'vue';
import { nextTick } from 'vue';

export function useElementFocus() {
    async function focus(elementRef: Ref<HTMLElement | null>): Promise<void> {
        if (!elementRef?.value) return;

        await nextTick();

        elementRef.value.focus();
    }

    async function focusAndSelect(elementRef: Ref<HTMLInputElement | HTMLTextAreaElement | null>): Promise<void> {
        if (!elementRef?.value) return;

        await nextTick();

        elementRef.value.select();
    }

    return { focus, focusAndSelect };
}
