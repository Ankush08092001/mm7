const mockTestsData = [
    {
        id: 'meo-class-4-written',
        title: 'MEO Class 4 Written Mock Test',
        type: 'written',
        badge: 'FREE',
        rating: 4.8,
        questions: 9,
        duration: '3 hours',
        attempted: 1245,
        description: 'Comprehensive mock test covering all MEO Class 4 written exam topics.',
        link: 'mock-test.html'
    },
    {
        id: 'safety-management',
        title: 'Safety Management Mock Test',
        type: 'written',
        badge: 'FREE',
        rating: 4.7,
        questions: 9,
        duration: '3 hours',
        attempted: 980,
        description: 'Focus on safety management systems and procedures.',
        link: 'mock-test.html'
    },
    {
        id: 'function-1-marine-engineering',
        title: 'Function 1 - Marine Engineering',
        type: 'written',
        badge: 'PREMIUM',
        rating: 4.9,
        questions: 9,
        duration: '3 hours',
        attempted: 1560,
        description: 'Advanced marine engineering concepts and applications.',
        link: 'mock-test.html'
    },
    {
        id: 'function-2-electrical',
        title: 'Function 2 - Electrical Systems',
        type: 'written',
        badge: 'PREMIUM',
        rating: 4.8,
        questions: 9,
        duration: '3 hours',
        attempted: 1120,
        description: 'Electrical systems, automation, and control systems.',
        link: 'mock-test.html'
    },
    {
        id: 'function-3-controlling',
        title: 'Function 3 - Controlling Operation',
        type: 'written',
        badge: 'PREMIUM',
        rating: 4.6,
        questions: 9,
        duration: '3 hours',
        attempted: 890,
        description: 'Ship operation, navigation, and cargo handling.',
        link: 'mock-test.html'
    },
    {
        id: 'function-4-maintenance',
        title: 'Function 4 - Maintenance & Repair',
        type: 'written',
        badge: 'PREMIUM',
        rating: 4.7,
        questions: 9,
        duration: '3 hours',
        attempted: 1340,
        description: 'Maintenance procedures and repair techniques.',
        link: 'mock-test.html'
    },
    {
        id: 'oral-interview-prep',
        title: 'Oral Interview Preparation',
        type: 'oral',
        badge: 'PREMIUM',
        rating: 4.9,
        questions: 'Varies',
        duration: '1-2 hours',
        attempted: 567,
        description: 'Practice oral examination scenarios and viva questions.',
        link: '#',
        comingSoon: true
    },
    {
        id: 'practical-assessment',
        title: 'Practical Assessment Simulation',
        type: 'oral',
        badge: 'PREMIUM',
        rating: 4.8,
        questions: 'Varies',
        duration: '2-3 hours',
        attempted: 234,
        description: 'Hands-on practical assessment simulation.',
        link: '#',
        comingSoon: true
    }
];

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = mockTestsData;
}

