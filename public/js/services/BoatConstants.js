export const BOAT_TYPES_ENUM = {
    MOTORBOOT: 1,
    SEGELBOOT: 2,
    KAJAK: 3,
    KANU: 4,
    SUP: 5,
};

export const BOAT_TYPE_ICONS = {
    [BOAT_TYPES_ENUM.MOTORBOOT]: '🚤',
    [BOAT_TYPES_ENUM.SEGELBOOT]: '⛵',
    [BOAT_TYPES_ENUM.KAJAK]:     '🛶',
    [BOAT_TYPES_ENUM.KANU]:      '🛶',
    [BOAT_TYPES_ENUM.SUP]:       '🏄',
};

export const BOAT_TYPES = {
    1: 'Segelboot',
    2: 'Motorboot',
    3: 'Kajak',
    4: 'Ruderboot',
    5: 'Katamaran',
};

export const AVAILABILITY = {
    1: 'Verfügbar',
    2: 'Reserviert',
    3: 'In Wartung',
    4: 'Außer Betrieb',
};