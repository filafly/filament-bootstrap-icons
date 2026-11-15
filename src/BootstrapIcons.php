<?php

namespace Filafly\Icons\Bootstrap;

use Filafly\Icons\Bootstrap\Enums\Bootstrap;
use Filafly\Icons\Bootstrap\Enums\BootstrapStyle;
use Filafly\Icons\IconSet;
use Filament\Support\Icons\IconManager;

class BootstrapIcons extends IconSet
{
    protected string $pluginId = 'filafly-filament-bootstrap-icons';

    protected mixed $iconEnum = Bootstrap::class;

    protected string $iconPrefix = 'bi';

    protected mixed $styleEnum = BootstrapStyle::class;

    /** Regular is the default, so style overrides work without calling regular() first. */
    protected mixed $currentStyle = BootstrapStyle::Regular;

    public function __construct()
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct();
        }

        // Filament 4.2+ renames the 4.0 and 4.1 tables:: query builder keys to query-builder::
        // when they're registered. Registering both would let the legacy key overwrite
        // an overrideAlias() on the current one, so keep a legacy key only where
        // Filament still treats it as its own alias.
        if (! method_exists(IconManager::class, 'normalizeAlias')) {
            return;
        }

        foreach (array_keys($this->iconMap) as $alias) {
            $normalized = IconManager::normalizeAlias($alias);

            if ($normalized !== $alias && array_key_exists($normalized, $this->iconMap)) {
                unset($this->iconMap[$alias]);
            }
        }
    }

    /**
     * Every Filament 4 and 5 icon alias, mapped to a Bootstrap icon.
     *
     * Keys are the alias strings rather than the *IconAlias constants, because
     * several constants (rich editor toolbar, multi-factor auth, callouts, query
     * builder) only exist in later 4.x releases. String keys keep the map loadable
     * on every Filament version the package supports.
     *
     * Icons are mapped to their regular (outline) version. fill() switches every
     * icon that has a -fill version; the rest stay as they are.
     */
    protected array $iconMap = [
        // Panels
        'panels::auth.multi-factor.app.actions.disable' => Bootstrap::ShieldX,
        'panels::auth.multi-factor.app.actions.disable.modal' => Bootstrap::ShieldX,
        'panels::auth.multi-factor.app.actions.disable.notification' => Bootstrap::ShieldX,
        'panels::auth.multi-factor.app.actions.regenerate-recovery-codes' => Bootstrap::ArrowRepeat,
        'panels::auth.multi-factor.app.actions.regenerate-recovery-codes.modal' => Bootstrap::ArrowRepeat,
        'panels::auth.multi-factor.app.actions.regenerate-recovery-codes.notification' => Bootstrap::Key,
        'panels::auth.multi-factor.app.actions.set-up' => Bootstrap::ShieldCheck,
        'panels::auth.multi-factor.app.actions.set-up.modal' => Bootstrap::ShieldCheck,
        'panels::auth.multi-factor.app.actions.set-up.notification' => Bootstrap::ShieldCheck,
        'panels::auth.multi-factor.email.actions.disable' => Bootstrap::EnvelopeX,
        'panels::auth.multi-factor.email.actions.disable.modal' => Bootstrap::EnvelopeX,
        'panels::auth.multi-factor.email.actions.disable.notification' => Bootstrap::EnvelopeX,
        'panels::auth.multi-factor.email.actions.set-up' => Bootstrap::EnvelopeCheck,
        'panels::auth.multi-factor.email.actions.set-up.modal' => Bootstrap::EnvelopeCheck,
        'panels::auth.multi-factor.email.actions.set-up.notification' => Bootstrap::EnvelopeCheck,
        'panels::global-search.field' => Bootstrap::Search,
        'panels::pages.dashboard.actions.filter' => Bootstrap::Funnel,
        'panels::pages.dashboard.navigation-item' => Bootstrap::House,
        'panels::pages.password-reset.request-password-reset.actions.login' => Bootstrap::ArrowLeft,
        'panels::pages.password-reset.request-password-reset.actions.login.rtl' => Bootstrap::ArrowRight,
        'panels::resources.pages.edit-record.navigation-item' => Bootstrap::PencilSquare,
        'panels::resources.pages.manage-related-records.navigation-item' => Bootstrap::Grid,
        'panels::resources.pages.view-record.navigation-item' => Bootstrap::Eye,
        'panels::sidebar.collapse-button' => Bootstrap::ChevronLeft,
        'panels::sidebar.collapse-button.rtl' => Bootstrap::ChevronRight,
        'panels::sidebar.expand-button' => Bootstrap::ChevronRight,
        'panels::sidebar.expand-button.rtl' => Bootstrap::ChevronLeft,
        'panels::sidebar.group.collapse-button' => Bootstrap::ChevronUp,
        'panels::sidebar.open-database-notifications-button' => Bootstrap::Bell,
        'panels::sub-navigation.mobile-menu.button' => Bootstrap::ChevronDown,
        'panels::tenant-menu.billing-button' => Bootstrap::CreditCard,
        'panels::tenant-menu.profile-button' => Bootstrap::Gear,
        'panels::tenant-menu.registration-button' => Bootstrap::PlusLg,
        'panels::tenant-menu.toggle-button' => Bootstrap::ChevronExpand,
        'panels::theme-switcher.dark-button' => Bootstrap::Moon,
        'panels::theme-switcher.light-button' => Bootstrap::Sun,
        'panels::theme-switcher.system-button' => Bootstrap::Display,
        'panels::topbar.close-sidebar-button' => Bootstrap::XLg,
        'panels::topbar.group.toggle-button' => Bootstrap::ChevronDown,
        'panels::topbar.open-database-notifications-button' => Bootstrap::Bell,
        'panels::topbar.open-sidebar-button' => Bootstrap::List,
        'panels::user-menu.logout-button' => Bootstrap::BoxArrowRight,
        'panels::user-menu.profile-item' => Bootstrap::PersonCircle,
        'panels::user-menu.toggle-button' => Bootstrap::ChevronDown,
        'panels::widgets.account.logout-button' => Bootstrap::BoxArrowRight,
        'panels::widgets.filament-info.open-documentation-button' => Bootstrap::Book,
        'panels::widgets.filament-info.open-github-button' => Bootstrap::Github,

        // Actions
        'actions::action-group' => Bootstrap::ThreeDotsVertical,
        'actions::create-action.grouped' => Bootstrap::PlusLg,
        'actions::delete-action' => Bootstrap::Trash,
        'actions::delete-action.grouped' => Bootstrap::Trash,
        'actions::delete-action.modal' => Bootstrap::Trash,
        'actions::detach-action' => Bootstrap::SlashCircle,
        'actions::detach-action.modal' => Bootstrap::SlashCircle,
        'actions::dissociate-action' => Bootstrap::SlashCircle,
        'actions::dissociate-action.modal' => Bootstrap::SlashCircle,
        'actions::edit-action' => Bootstrap::PencilSquare,
        'actions::edit-action.grouped' => Bootstrap::PencilSquare,
        'actions::export-action.grouped' => Bootstrap::Download,
        'actions::force-delete-action' => Bootstrap::Trash,
        'actions::force-delete-action.grouped' => Bootstrap::Trash,
        'actions::force-delete-action.modal' => Bootstrap::Trash,
        'actions::import-action.grouped' => Bootstrap::Upload,
        'actions::modal.confirmation' => Bootstrap::ExclamationTriangle,
        'actions::replicate-action' => Bootstrap::Copy,
        'actions::replicate-action.grouped' => Bootstrap::Copy,
        'actions::restore-action' => Bootstrap::ArrowCounterclockwise,
        'actions::restore-action.grouped' => Bootstrap::ArrowCounterclockwise,
        'actions::restore-action.modal' => Bootstrap::ArrowCounterclockwise,
        'actions::view-action' => Bootstrap::Eye,
        'actions::view-action.grouped' => Bootstrap::Eye,

        // Forms
        'forms::components.builder.actions.clone' => Bootstrap::Copy,
        'forms::components.builder.actions.collapse' => Bootstrap::ChevronUp,
        'forms::components.builder.actions.delete' => Bootstrap::Trash,
        'forms::components.builder.actions.edit' => Bootstrap::PencilSquare,
        'forms::components.builder.actions.expand' => Bootstrap::ChevronDown,
        'forms::components.builder.actions.move-down' => Bootstrap::ArrowDown,
        'forms::components.builder.actions.move-up' => Bootstrap::ArrowUp,
        'forms::components.builder.actions.reorder' => Bootstrap::GripVertical,
        'forms::components.checkbox-list.search-field' => Bootstrap::Search,
        'forms::components.file-upload.editor.actions.drag-crop' => Bootstrap::Crop,
        'forms::components.file-upload.editor.actions.drag-move' => Bootstrap::ArrowsMove,
        'forms::components.file-upload.editor.actions.flip-horizontal' => Bootstrap::SymmetryVertical,
        'forms::components.file-upload.editor.actions.flip-vertical' => Bootstrap::SymmetryHorizontal,
        'forms::components.file-upload.editor.actions.move-down' => Bootstrap::ArrowDownCircle,
        'forms::components.file-upload.editor.actions.move-left' => Bootstrap::ArrowLeftCircle,
        'forms::components.file-upload.editor.actions.move-right' => Bootstrap::ArrowRightCircle,
        'forms::components.file-upload.editor.actions.move-up' => Bootstrap::ArrowUpCircle,
        'forms::components.file-upload.editor.actions.rotate-left' => Bootstrap::ArrowCounterclockwise,
        'forms::components.file-upload.editor.actions.rotate-right' => Bootstrap::ArrowClockwise,
        'forms::components.file-upload.editor.actions.zoom-100' => Bootstrap::AspectRatio,
        'forms::components.file-upload.editor.actions.zoom-in' => Bootstrap::ZoomIn,
        'forms::components.file-upload.editor.actions.zoom-out' => Bootstrap::ZoomOut,
        'forms::components.key-value.actions.delete' => Bootstrap::Trash,
        'forms::components.key-value.actions.reorder' => Bootstrap::GripVertical,
        'forms::components.modal-table-select.actions.select' => Bootstrap::Cursor,
        'forms::components.repeater.actions.clone' => Bootstrap::Copy,
        'forms::components.repeater.actions.collapse' => Bootstrap::ChevronUp,
        'forms::components.repeater.actions.delete' => Bootstrap::Trash,
        'forms::components.repeater.actions.expand' => Bootstrap::ChevronDown,
        'forms::components.repeater.actions.move-down' => Bootstrap::ArrowDown,
        'forms::components.repeater.actions.move-up' => Bootstrap::ArrowUp,
        'forms::components.repeater.actions.reorder' => Bootstrap::GripVertical,
        'forms::components.rich-editor.panels.custom-block.delete-button' => Bootstrap::Trash,
        'forms::components.rich-editor.panels.custom-block.edit-button' => Bootstrap::PencilSquare,
        'forms::components.rich-editor.panels.custom-blocks.close-button' => Bootstrap::XLg,
        'forms::components.rich-editor.panels.merge-tags.close-button' => Bootstrap::XLg,
        'forms::components.select.actions.create-option' => Bootstrap::PlusLg,
        'forms::components.select.actions.edit-option' => Bootstrap::PencilSquare,
        'forms::components.text-input.actions.copy' => Bootstrap::Copy,
        'forms::components.text-input.actions.hide-password' => Bootstrap::EyeSlash,
        'forms::components.text-input.actions.show-password' => Bootstrap::Eye,
        'forms::components.toggle-buttons.boolean.false' => Bootstrap::XLg,
        'forms::components.toggle-buttons.boolean.true' => Bootstrap::CheckLg,

        // Forms: rich editor toolbar (Filament's own aliases use a single colon here)
        'forms:components.rich-editor.toolbar.align-center' => Bootstrap::TextCenter,
        'forms:components.rich-editor.toolbar.align-end' => Bootstrap::TextRight,
        'forms:components.rich-editor.toolbar.align-justify' => Bootstrap::Justify,
        'forms:components.rich-editor.toolbar.align-start' => Bootstrap::TextLeft,
        'forms:components.rich-editor.toolbar.attach-files' => Bootstrap::Paperclip,
        'forms:components.rich-editor.toolbar.blockquote' => Bootstrap::Quote,
        'forms:components.rich-editor.toolbar.bold' => Bootstrap::TypeBold,
        'forms:components.rich-editor.toolbar.bullet-list' => Bootstrap::ListUl,
        'forms:components.rich-editor.toolbar.clear_formatting' => Bootstrap::Eraser,
        'forms:components.rich-editor.toolbar.code' => Bootstrap::Code,
        'forms:components.rich-editor.toolbar.code-block' => Bootstrap::CodeSquare,
        'forms:components.rich-editor.toolbar.custom-blocks' => Bootstrap::Puzzle,
        'forms:components.rich-editor.toolbar.details' => Bootstrap::CardList,
        'forms:components.rich-editor.toolbar.grid' => Bootstrap::LayoutThreeColumns,
        'forms:components.rich-editor.toolbar.grid_add_column_after' => Bootstrap::DistributeHorizontal,
        'forms:components.rich-editor.toolbar.grid_add_column_before' => Bootstrap::DistributeHorizontal,
        'forms:components.rich-editor.toolbar.grid_delete' => Bootstrap::Trash,
        'forms:components.rich-editor.toolbar.grid_delete_column' => Bootstrap::Trash,
        'forms:components.rich-editor.toolbar.h1' => Bootstrap::TypeH1,
        'forms:components.rich-editor.toolbar.h2' => Bootstrap::TypeH2,
        'forms:components.rich-editor.toolbar.h3' => Bootstrap::TypeH3,
        'forms:components.rich-editor.toolbar.h4' => Bootstrap::TypeH4,
        'forms:components.rich-editor.toolbar.h5' => Bootstrap::TypeH5,
        'forms:components.rich-editor.toolbar.h6' => Bootstrap::TypeH6,
        'forms:components.rich-editor.toolbar.highlight' => Bootstrap::Highlighter,
        'forms:components.rich-editor.toolbar.horizontal-rule' => Bootstrap::Hr,
        'forms:components.rich-editor.toolbar.italic' => Bootstrap::TypeItalic,
        'forms:components.rich-editor.toolbar.lead' => Bootstrap::Type,
        'forms:components.rich-editor.toolbar.link' => Bootstrap::Link45deg,
        'forms:components.rich-editor.toolbar.merge-tags' => Bootstrap::Braces,
        'forms:components.rich-editor.toolbar.ordered-list' => Bootstrap::ListOl,
        'forms:components.rich-editor.toolbar.paragraph' => Bootstrap::Paragraph,
        'forms:components.rich-editor.toolbar.redo' => Bootstrap::ArrowClockwise,
        'forms:components.rich-editor.toolbar.small' => Bootstrap::Fonts,
        'forms:components.rich-editor.toolbar.strike' => Bootstrap::TypeStrikethrough,
        'forms:components.rich-editor.toolbar.subscript' => Bootstrap::Subscript,
        'forms:components.rich-editor.toolbar.superscript' => Bootstrap::Superscript,
        'forms:components.rich-editor.toolbar.table' => Bootstrap::Table,
        'forms:components.rich-editor.toolbar.table_add_column_after' => Bootstrap::DistributeHorizontal,
        'forms:components.rich-editor.toolbar.table_add_column_before' => Bootstrap::DistributeHorizontal,
        'forms:components.rich-editor.toolbar.table_add_row_after' => Bootstrap::DistributeVertical,
        'forms:components.rich-editor.toolbar.table_add_row_before' => Bootstrap::DistributeVertical,
        'forms:components.rich-editor.toolbar.table_delete' => Bootstrap::Trash,
        'forms:components.rich-editor.toolbar.table_delete_column' => Bootstrap::Trash,
        'forms:components.rich-editor.toolbar.table_delete_row' => Bootstrap::Trash,
        'forms:components.rich-editor.toolbar.table_merge_cells' => Bootstrap::LayoutSplit,
        'forms:components.rich-editor.toolbar.table_split_cell' => Bootstrap::WindowSplit,
        'forms:components.rich-editor.toolbar.table_toggle_header_cell' => Bootstrap::LayoutSidebarInset,
        'forms:components.rich-editor.toolbar.table_toggle_header_row' => Bootstrap::ViewList,
        'forms:components.rich-editor.toolbar.text-color' => Bootstrap::Palette,
        'forms:components.rich-editor.toolbar.underline' => Bootstrap::TypeUnderline,
        'forms:components.rich-editor.toolbar.undo' => Bootstrap::ArrowCounterclockwise,

        // Infolists
        'infolists::components.icon-entry.false' => Bootstrap::XCircle,
        'infolists::components.icon-entry.true' => Bootstrap::CheckCircle,

        // Notifications
        'notifications::database.modal.empty-state' => Bootstrap::BellSlash,
        'notifications::notification.close-button' => Bootstrap::XLg,
        'notifications::notification.danger' => Bootstrap::ExclamationTriangle,
        'notifications::notification.info' => Bootstrap::InfoCircle,
        'notifications::notification.success' => Bootstrap::CheckCircle,
        'notifications::notification.warning' => Bootstrap::ExclamationCircle,

        // Query builder (Filament 4.0 used the tables:: prefix for the constraints)
        'query-builder::add-rule-action' => Bootstrap::PlusLg,
        'query-builder::constraints.boolean' => Bootstrap::ToggleOn,
        'query-builder::constraints.date' => Bootstrap::Calendar,
        'query-builder::constraints.number' => Bootstrap::Hash,
        'query-builder::constraints.relationship' => Bootstrap::Link45deg,
        'query-builder::constraints.select' => Bootstrap::ChevronExpand,
        'query-builder::constraints.text' => Bootstrap::Fonts,
        'query-builder::or-group.add-group-action' => Bootstrap::PlusLg,
        'query-builder::or-group.block' => Bootstrap::Stack,
        'tables::filters.query-builder.constraints.boolean' => Bootstrap::ToggleOn,
        'tables::filters.query-builder.constraints.date' => Bootstrap::Calendar,
        'tables::filters.query-builder.constraints.number' => Bootstrap::Hash,
        'tables::filters.query-builder.constraints.relationship' => Bootstrap::Link45deg,
        'tables::filters.query-builder.constraints.select' => Bootstrap::ChevronExpand,
        'tables::filters.query-builder.constraints.text' => Bootstrap::Fonts,

        // Schemas
        'schema::components.callout.danger' => Bootstrap::ExclamationTriangle,
        'schema::components.callout.info' => Bootstrap::InfoCircle,
        'schema::components.callout.success' => Bootstrap::CheckCircle,
        'schema::components.callout.warning' => Bootstrap::ExclamationCircle,
        'schema::components.tabs.dropdown-trigger-button' => Bootstrap::ChevronDown,
        'schema::components.tabs.more-tabs-button' => Bootstrap::ThreeDots,
        'schema::components.wizard.completed-step' => Bootstrap::CheckLg,

        // Support
        'badge.delete-button' => Bootstrap::XLg,
        'breadcrumbs.separator' => Bootstrap::ChevronRight,
        'breadcrumbs.separator.rtl' => Bootstrap::ChevronLeft,
        'modal.close-button' => Bootstrap::XLg,
        'pagination.first-button' => Bootstrap::ChevronDoubleLeft,
        'pagination.first-button.rtl' => Bootstrap::ChevronDoubleRight,
        'pagination.last-button' => Bootstrap::ChevronDoubleRight,
        'pagination.last-button.rtl' => Bootstrap::ChevronDoubleLeft,
        'pagination.next-button' => Bootstrap::ChevronRight,
        'pagination.next-button.rtl' => Bootstrap::ChevronLeft,
        'pagination.previous-button' => Bootstrap::ChevronLeft,
        'pagination.previous-button.rtl' => Bootstrap::ChevronRight,
        'section.collapse-button' => Bootstrap::ChevronUp,

        // Tables
        'tables::actions.column-manager' => Bootstrap::LayoutThreeColumns,
        'tables::actions.disable-reordering' => Bootstrap::CheckLg,
        'tables::actions.enable-reordering' => Bootstrap::ArrowDownUp,
        'tables::actions.filter' => Bootstrap::Funnel,
        'tables::actions.group' => Bootstrap::Stack,
        'tables::actions.open-bulk-actions' => Bootstrap::ThreeDotsVertical,
        'tables::columns.collapse-button' => Bootstrap::ChevronDown,
        'tables::columns.icon-column.false' => Bootstrap::XCircle,
        'tables::columns.icon-column.true' => Bootstrap::CheckCircle,
        'tables::empty-state' => Bootstrap::XLg,
        'tables::filters.remove-all-button' => Bootstrap::XLg,
        'tables::grouping.collapse-button' => Bootstrap::ChevronUp,
        'tables::header-cell.sort-asc-button' => Bootstrap::ChevronUp,
        'tables::header-cell.sort-button' => Bootstrap::ChevronExpand,
        'tables::header-cell.sort-desc-button' => Bootstrap::ChevronDown,
        'tables::reorder.handle' => Bootstrap::GripVertical,
        'tables::search-field' => Bootstrap::Search,

        // Widgets
        'widgets::chart-widget.empty-state' => Bootstrap::BarChart,
        'widgets::chart-widget.filter' => Bootstrap::Funnel,
    ];
}
