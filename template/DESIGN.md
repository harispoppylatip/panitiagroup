---
name: Cognitive Clarity
colors:
  surface: '#f8f9ff'
  surface-dim: '#ccdbf3'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e6eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d5e3fc'
  on-surface: '#0d1c2e'
  on-surface-variant: '#434655'
  inverse-surface: '#233144'
  inverse-on-surface: '#eaf1ff'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#5c5f61'
  on-secondary: '#ffffff'
  secondary-container: '#e0e3e5'
  on-secondary-container: '#626567'
  tertiary: '#943700'
  on-tertiary: '#ffffff'
  tertiary-container: '#bc4800'
  on-tertiary-container: '#ffede6'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#e0e3e5'
  secondary-fixed-dim: '#c4c7c9'
  on-secondary-fixed: '#191c1e'
  on-secondary-fixed-variant: '#444749'
  tertiary-fixed: '#ffdbcd'
  tertiary-fixed-dim: '#ffb596'
  on-tertiary-fixed: '#360f00'
  on-tertiary-fixed-variant: '#7d2d00'
  background: '#f8f9ff'
  on-background: '#0d1c2e'
  surface-variant: '#d5e3fc'
typography:
  headline-xl:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 16px
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 64px
---

## Brand & Style
The design system is centered on **Functional Minimalism** with a focus on high legibility and cognitive ease. The target audience includes professional users who require a high-efficiency interface that feels approachable rather than intimidating.

The aesthetic is characterized by:
- **Clarity over Decoration:** Every element must serve a functional purpose. Remove borders or shadows that do not assist in grouping or hierarchy.
- **Approachable Professionalism:** A balance of strict alignment and soft geometry to evoke feelings of reliability and friendliness.
- **Intentional Whitespace:** Generous padding is used to prevent information density from overwhelming the user, ensuring the interface feels "breathable."

## Colors
The palette is dominated by white and high-tint grays to maximize the "clean" aesthetic. 

- **Primary Blue (#2563eb):** Reserved for primary actions, active states, and critical information. It provides a strong focal point against the neutral background.
- **Surface Neutrals:** Use a scale of Cool Grays (Slate) for text and subtle borders. 
- **Interaction States:** Hover states should utilize a subtle shift in luminance (lighter for dark elements, darker for light elements) rather than introducing new hues.
- **Accessibility:** All text-on-background combinations must maintain a minimum contrast ratio of 4.5:1, with the primary blue reaching 7:1 against white for maximum accessibility.

## Typography
This design system utilizes **Inter** for its exceptional legibility and neutral, modern character. 

- **Weight Usage:** Reserve Bold (700) and Semi-Bold (600) for headlines and functional labels. Use Regular (400) for all long-form body text.
- **Scale:** The scale follows a strict rhythmic progression. On mobile, large headlines drop significantly in size to prevent awkward line breaks and ensure the content remains the focus.
- **Letter Spacing:** Apply slight negative tracking to larger headlines to maintain a cohesive visual block, while keeping body text at zero tracking for optimal readability.

## Layout & Spacing
The system employs a **8px soft grid** for internal component spacing and a **12-column fluid grid** for page layout.

- **Grid Architecture:** On desktop, use a max-width container of 1280px with 24px gutters. On mobile, switch to a 4-column layout with 16px margins.
- **Spacing Rhythm:** Use "md" (16px) as the standard padding for cards and containers. Increase to "lg" (24px) for sections to provide the requested "plenty of whitespace."
- **Alignment:** All elements must snap to the 4px baseline to ensure visual consistency across different browsers and devices.

## Elevation & Depth
Depth is conveyed through **Low-contrast Outlines** and **Ambient Shadows**. This prevents the UI from feeling cluttered while still providing a sense of layering.

- **Shadow Character:** Use a single, highly diffused shadow style. The shadow should be `0 4px 12px rgba(0, 0, 0, 0.05)`. It must be barely perceptible, serving only to lift the element off the background.
- **Outlines:** All containers should have a 1px solid border in `#e2e8f0` (Slate 200). This provides structure without the visual weight of a heavy shadow.
- **Z-Index Strategy:** Only use elevation for interactive elements like cards, modals, and dropdowns. Static page sections remain flat on the background layer.

## Shapes
The shape language is consistently **Rounded**, utilizing an 8px radius for standard components and a 12px-16px radius for larger containers (cards, modals).

- **Standard Radius:** 8px (`0.5rem`) for buttons, inputs, and small chips.
- **Large Radius:** 16px (`1rem`) for primary content cards.
- **Full Radius:** Use pill shapes (999px) only for status indicators or specific badge types to differentiate them from interactive buttons.

## Components
- **Buttons:** Primary buttons use the Primary Blue background with white text. Padding is `12px 24px`. No gradients; use a flat color.
- **Input Fields:** 1px border (#e2e8f0). On focus, the border changes to Primary Blue with a subtle 2px blue glow (ring). Use a 16px font size for inputs to prevent iOS auto-zoom.
- **Cards:** White background, 1px border, 16px corner radius, and the standard ambient shadow. Internal padding should be at least 24px.
- **Lists:** Use 1px bottom borders for list items, but remove the border for the last item in a group to keep the design clean.
- **Chips:** Soft-gray background (#f1f5f9) with Semi-Bold text. 8px radius.
- **Checkboxes/Radios:** Use the Primary Blue for the active state. Ensure the hit target is at least 44x44px for accessibility.
- **Accessibility:** All interactive components must have a visible `:focus-visible` state using a 2px offset ring in Primary Blue.