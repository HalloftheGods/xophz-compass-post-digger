# Xophz Lemon-Aid (Post Digger)

> **Category:** Trajectory · **Version:** 0.0.1

An RSS feed aggregator and user curation spark natively integrated into YouMeOS.

## Description

**Lemon-Aid** (technically known as `xophz-compass-post-digger`) is an RSS aggregation engine for YouMeOS. It serves a dual purpose: operating as a personal feed reader where users can organize their preferred news sources, and acting as a crowdsourced curation engine for the global "Noosphere" collective feed.

### Core Capabilities

- **RSS Aggregation** – Background cron jobs automatically fetch the latest XML updates from configured RSS feeds.
- **The Sugar vs Lemon Mechanic** – Gamified user feedback on incoming stories:
  - **Sugar (Upvote):** Positive reinforcement for high-quality content.
  - **Lemon (Downvote):** Negative feedback for low-quality or irrelevant content.
- **Noosphere Promotion** – Stories that meet a specific "Sweetness Ratio" (more sugar than lemon) are promoted dynamically to the global Noosphere feed.
- **REST API** – Normalizes feed items and emits them via REST for consumption by the Vue-based Lemon-Aid Spark.

## Requirements

- **Xophz COMPASS** parent plugin (active)
- WordPress 5.8+, PHP 7.4+

## Installation

1. Ensure **Xophz COMPASS** is installed and active.
2. Upload `xophz-compass-post-digger` to `/wp-content/plugins/`.
3. Activate through the Plugins menu.
4. Access via the My Compass dashboard → **Lemon-Aid**.

## PHP Class Map

| Class | File | Purpose |
|---|---|---|
| `Xophz_Compass_Post_Digger` | `class-xophz-compass-post-digger.php` | Core plugin hooks, cron processing, and RSS fetching |
| `Xophz_Compass_Post_Digger_API` | `class-xophz-compass-post-digger-api.php` | REST API for feeds, fetching, and Sugar/Lemon voting |

## Frontend Routes

| Route | View | Description |
|---|---|---|
| `/lemon-aid` | Dashboard | Personal feed reader, Noosphere feed, and curation interface |

## Changelog

### 0.0.1

- Initial release with RSS fetching, REST API, and Sugar/Lemon voting.
