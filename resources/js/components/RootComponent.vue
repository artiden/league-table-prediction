<template>
    <div>
        <h1>League simulation</h1>
        <div v-if="noTeams">
            No teams to play. Click button to generate it.
            <button v-if="noTeams" @click="generateTeams()">Generate teams</button>
        </div>

        <button v-if="!noTeams" @click="reset()">Reset</button>

        <!-- League table -->
        <LeagueTable v-if="teams?.length" :teams="teams" />

        <button v-if="!noTeams" @click="simulateWeek(week+1)">Next week</button>

        <!-- Matches Table -->
        <MatchTable v-if="matches?.length" :matches="matches" :week="week" />

        <!-- Predictions Table -->
        <PredictionTable v-if="predictions?.length" :predictions="predictions" />
        <button v-if="!noTeams" @click="playAll">Play all</button>
        <a v-if="week > 1" :href="historyUrl">See all matches</a>
    </div>
</template>

<script>
import api from '../axiosConfig';
import LeagueTable from './LeagueTable.vue';
import MatchTable from './MatchTable.vue';
import PredictionTable from './PredictionTable.vue';

export default {
    components: {
        LeagueTable,
        MatchTable,
        PredictionTable,
    },
    props: {
        historyUrl: String,
    },
    data() {
        return {
            teams: [],
            matches: [],
            predictions: [],
            week: 1,
            noTeams: true,
        };
    },
    methods: {
        async simulateWeek(week) {
            try {
                const response = await api.post('/api/v1/simulate', {
                    week
                });
                const { league: teams, fictures: matches, predictions, week: responseWeek } = response.data;

                this.teams = teams;
                this.matches = matches;
                this.predictions = predictions;
                this.week = responseWeek;
                this.noTeams = false;
            } catch (error) {
                console.error('Unable to get data:', error);
                const { noTeams } = error.data;
                this.noTeams = noTeams;
            }
        },
        async playAll() {
            const startWeek = this.week;
            const maxWeeks = 10;
            for (let i = startWeek; i <= maxWeeks; i++) {
                await this.simulateWeek(i);
            }
        },
        async generateTeams() {
            try {
                const response = api.post('/api/v1/generate-teams');
                this.simulateWeek(null);
            } catch (error) {
                console.log('Unable to regenerate teams:', error);
            }
        },
        async reset() {
            try {
                const response = api.post('/api/v1/reset');
                this.simulateWeek(null);
            } catch (error) {
                console.log('Unable to reset simulation:', error);
            }
        }
    },
    mounted() {
        this.simulateWeek(null);
    },
};
</script>
