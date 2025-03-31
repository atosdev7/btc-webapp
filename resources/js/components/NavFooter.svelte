<script lang="ts">
    import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
    import type { NavItem } from '@/types';
    import { Link, page } from '@inertiajs/svelte';
    
    interface Props {
        items: NavItem[];
        class?: string;
    }

    let { items, class: className }: Props = $props();
</script>

<SidebarGroup class="group-data-[collapsible=icon]:p-0 {className}">
    <SidebarGroupContent>
        <SidebarMenu>
            {#each items as item, index (index)}
                <SidebarMenuItem>
                    <Link href={item.href} class="block w-full">
                        <SidebarMenuButton isActive={item.href === $page.url}>
                            {#if item.icon}
                                {@const Icon = item.icon}
                                <Icon class="h-4 w-4 shrink-0" />
                            {/if}
                            <span>{item.title}</span>
                        </SidebarMenuButton>
                    </Link>
                </SidebarMenuItem>
            {/each}
        </SidebarMenu>
    </SidebarGroupContent>
</SidebarGroup>
